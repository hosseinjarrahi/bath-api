<?php

namespace Modules\Service\Controllers;

use Modules\Service\Models\ServiceSetting;
use Modules\Service\Models\PassengerForm;
use Modules\Service\Models\ServiceOption;
use Modules\Service\Models\ServiceItem;
use Modules\Service\Models\Passenger;
use App\Http\Controllers\Controller;
use Modules\Payment\Models\Payment;
use Modules\Service\Models\Service;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ServiceController extends Controller
{
    function saveForm()
    {
        \DB::beginTransaction();

        $serviceItems = request('service_items');
        $passengerCodeMeli = request('code_meli');
        $passengerName = request('name');

        $passenger = Passenger::firstOrCreate(
            [
                'code_meli' => $passengerCodeMeli,
            ],
            [
                'name' => $passengerName,
            ]
        );

        $activeForm = $passenger->passengerForm();

        if ($activeForm) {
            return response(['message' => 'متاسفانه شما یک فرم فعال دارید'], Response::HTTP_BAD_REQUEST);
        }

        $price = 0;
        $exitTime = ServiceSetting::where('key', 'exit_time')->first();
        $serviceItemPrice = ServiceSetting::where('key', 'service_item_price')->first();

        $startDate = now();
        $startDate->setTimeFromTimeString(request('start_date'));
        $endDate = clone ($startDate);

        $newForm = passengerForm::create([
            'passenger_id' => $passenger->id,
            'start_date' => $startDate,
            'end_date' => $endDate->addMinutes($exitTime->value),
        ]);

        $options = request('options');

        foreach ($options as $option) {
            $opt = ServiceOption::find($option['id']);
            $price += ((int) $option['counter']) * $opt->price;
            $newForm->serviceOptions()->attach($opt->id, ['count' => $option['counter']]);
        }

        $newForm->update(['options_price' => $price]);

        $price = 0;
        foreach ($serviceItems as $serviceItem) {
            $price += (int) $serviceItemPrice->value;

            $serviceItem = ServiceItem::find($serviceItem['id']);

            if ($serviceItem->price) {
                $price += $price;
            }

            $serviceItem->update([
                'passenger_id' => $passenger->id,
            ]);

            $newForm->serviceItems()->save($serviceItem);
        }

        $newForm->update(['service_price' => $price]);

        \DB::commit();
    }

    private function calculateDelayPrice($checkoutDate, $endDate)
    {
        $delayPrice = 0;
        $diff = 0;
        $endDate = new Carbon($endDate);
        $checkoutDate = new Carbon($checkoutDate);

        if ($checkoutDate->gt($endDate)) {
            $diff = $checkoutDate->diffInMinutes($endDate);
            if ($diff >= 1) {
                $delayPrice = ServiceSetting::where('key', 'delay_price')->first();
                $delayPrice = $diff * ((int) $delayPrice->value);
            }
        }

        return [$delayPrice, $diff];
    }

    public function checkout(Request $request)
    {
        \DB::beginTransaction();

        $passengerForm = PassengerForm::with('passenger')->find($request->passenger_form_id);

        [$delayPrice, $diff] = $this->calculateDelayPrice($request->checkout_date, $passengerForm->end_date);

        if (!$request->checked) {
            return ['delay_price' => $delayPrice, 'diff' => $diff];
        }

        $checkout_date = now();
        $checkout_date->setTimeFromTimeString($request->checkout_date);

        $passengerForm->update([
            'price' => $passengerForm->price,
            'checkout_date' => $checkout_date,
            'damage' => $request->damage,
            'delay_price' => $delayPrice,
        ]);

        $passengerForm->serviceItems()->update(['passenger_id' => null]);

        $passengerForm->serviceItems()->updateExistingPivot($passengerForm, ['active' => false]);

        $passengerForm->serviceItems()->sync([]);

        \DB::commit();

        return ['passenger' => $passengerForm->passenger->refresh()->load(['serviceItems']), 'message' => 'با موفقیت ثبت شد'];
    }

    public function analyse()
    {
        $emptyServiceItems = ServiceItem::whereHas('service')->whereNull('passenger_id')->count();

        $fullServiceItems = ServiceItem::whereHas('service')->whereNotNull('passenger_id')->count();

        return [
            'emptyServiceItems' => $emptyServiceItems,
            'fullServiceItems' => $fullServiceItems,
            'serviceItems' => $fullServiceItems + $emptyServiceItems,
        ];
    }

    function policy()
    {
        $maxReserveDays = ServiceSetting::where('key', 'max_reserve_day')->first();
        $maxReserveBeforeDate = ServiceSetting::where('key', 'max_reserve_before_date')->first();

        $startDate = now()->addDays($maxReserveBeforeDate->value)->subDay();
        $endDate = (clone $startDate)->addDays($maxReserveDays->value);

        return [
            'start_date' => $startDate,
            'start_date_plus_one' => (clone $startDate)->addDay(),
            'end_date' => $endDate,
        ];
    }

    function reservableServiceItems(Request $request)
    {
        $startDate = now();

        $startDate->setTimeFromTimeString(request('start_date'));

        $services = Service::with(
            [
                'serviceItems' =>
                    fn($q) => $q->whereDoesntHave('passengerForms', fn($q2) => $q2->where('end_date', '>', $startDate))
            ]
        )->get();

        return ['Service' => $services];
    }

    function report()
    {
        $forms = PassengerForm::with(['serviceItems.service', 'passenger'])
            ->whereBetween('start_date', [now()->subMonth(), now()])
            ->get();

        $serviceItems = [];
        $passengers = [];
        foreach ($forms as $form) {
            if (!isset($passengers[$form->passenger_id])) {
                $passengers[$form->passenger_id] = [
                    'count' => 0,
                    'passenger' => $form->passenger
                ];
            }
            $passengers[$form->passenger_id]['count']++;

            foreach ($form->serviceItems as $serviceItem) {
                if (!isset($serviceItems[$serviceItem->id])) {
                    $serviceItems[$serviceItem->id] = [
                        'count' => 0,
                        'service_item' => $serviceItem
                    ];
                }
                $serviceItems[$serviceItem->id]['count']++;
            }
        }

        $passengers = collect($passengers)->sortByDesc('count')->take(3);

        $serviceItems = collect($serviceItems)->sortByDesc('count')->take(3);
        $lowServiceItems = collect($serviceItems)->sortBy('count')->take(3);

        $monthtPayments = Payment::whereBetween('created_at', [now()->subMonth(), now()])
            ->where('paymentable_type', PassengerForm::class)
            ->sum('amount');

        $weekPayments = Payment::whereBetween('created_at', [now()->subWeek(), now()])
            ->where('paymentable_type', PassengerForm::class)
            ->sum('amount');

        $dayPayments = Payment::whereBetween('created_at', [now()->subMonth(), now()])
            ->where('paymentable_type', PassengerForm::class)
            ->avg('amount');

        return [
            'service_items' => $serviceItems->toArray(),
            'low_service_items' => $lowServiceItems->toArray(),
            'passengers' => $passengers->toArray(),
            'payments' => [
                'montht_payments' => (int) $monthtPayments,
                'week_payments' => (int) $weekPayments,
                'day_payments' => (int) $dayPayments,
            ]
        ];
    }

    function shouldCheckout()
    {
        $passengerForms = PassengerForm::where('end_date', '<=', now())
            ->whereNull('checkout_date')
            ->paginate(100);

        return ['PassengerForm' => $passengerForms];
    }

    function code($code)
    {
        $driver = Passenger::where('code_meli', $code)->first();

        return [
            'name' => $driver->name ?? ''
        ];
    }
}
