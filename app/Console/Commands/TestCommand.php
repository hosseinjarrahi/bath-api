<?php

namespace App\Console\Commands;

use Modules\Collector\Models\TabarInvoiceContainer;
use Modules\Ocr\Models\TruckLog;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    protected $signature = 'tester';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    function add_spaces_between_numbers_and_alphas($input_string)
    {
        $digits = (int) filter_var($input_string, FILTER_SANITIZE_NUMBER_INT);
        $words = preg_replace('/\d+/u', '', $input_string);

        return $words . ' ' . $digits;
    }

    function old()
    {
        $logs = TruckLog::latest()->limit(500)->get();

        foreach ($logs as $log) {
            $code = $log->container_code ? $log->container_code : $log->container_code_2;

            $code = $code ? $code : '';

            $exploded = explode(',', $code);

            if (count($exploded) < 4)
                continue;

            $code = $code[2] . $code[3];

            $code = $this->add_spaces_between_numbers_and_alphas($code);
            dump($code);
            $containerItem = TabarInvoiceContainer::where('containerNo', $code)->first();

            if (!$containerItem) {
                dump($containerItem->toArray());
                $this->confirm('Do you wish to continue?');
            }
        }
    }


    public function handle()
    {
        dump('searching');

        $res = \Http::get('https://damavand.rasm.io/api/v3/Search/Search?textSearch=' .'آریا ترابر');

        $res = $res->json();

        $companies = $res['companies']['data'] ?? [];
        $people = $res['people']['data'] ?? [];

        $truckLogs = TruckLog::whereDate('log_time', '>', now()->subDays(7))
            ->whereNotNull('container_show')
            ->whereHas('ccs', fn($q) => $q->whereDoesntHave('tabariInvoice'))
            ->first();
        dd(
            $truckLogs
        );
    }
}
