<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InvoiceImport implements ToModel, WithHeadingRow, WithChunkReading
{
    public function chunkSize(): int
    {
        return 10000;
    }

    public function model(array $row)
    {
        $map = [
            'nam_khrydar' => 'customer_name',
            'kbd_anbar' => 'r_number',
            'brdakht' => 'pay_date',
            'mblgh' => 'amount',
            'shnash_khrydar' => 'national',
        ];

        foreach ($map as $key => $value) {
            $row[$value] = $row[$key] ?? null;
        }

        if ($row['pay_date'] == null && $row['customer_name'] == null && $row['amount'] == null)
            return;

        if ($row['amount'] <= 0)
            return;

        return InvoiceBandar::create([
            'ParkingCost' => $row['amount'],
            'PaymentDate' => $row['pay_date'] ? verta()->parse($row['pay_date'])->toCarbon() : now(),
            'ReceiptNumber' => $row['r_number'],
            'GoodsOwnerName' => $row['customer_name'],
            'GoodsOwnerNationalID' => $row['national'],
        ]);
    }
}
