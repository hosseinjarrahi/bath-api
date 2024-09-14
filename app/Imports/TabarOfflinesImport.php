<?php

namespace App\Imports;

use Modules\Collector\Models\TabarOffline;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class TabarOfflinesImport implements ToModel, WithChunkReading, WithStartRow
{
    public function startRow(): int
    {
        return 38898; // شروع از ردیف 1
    }

    public function model(array $row)
    {
        // بررسی وجود رکورد با همان tracking_code، date و time
        $existingTransaction = TabarOffline::where('Invoice_number', $row[14])
            ->where('ReceiptNumber', $row[13])
            ->first();

        // اگر رکورد وجود داشت، آن را ذخیره نکنید
        if ($existingTransaction) {
            return null;
        }
        return new TabarOffline([
            "Invoice_number" => $row[14],
            "ReceiptNumber" => $row[13],
            "AbandonmentDate" => $row[12],
            "InvoiceDate" => $row[11],
            "PaymentDate" => $row[10],
            "Total" => $row[9],
            // "Tax" => $row[8],
            "PayRequestTraceNo" => $row[7],
            "SystemTraceNumber" => $row[5],
            "GoodsOwnerNationalID" => $row[6],
            "GoodsOwnerName" => $row[4],
            "GoodsOwnerPostalCode" => $row[2],
            "GoodsOwnerEconommicCode" => $row[3],
            "20f" => $row[1],
            "40f" => $row[0]
        ]);
    }

    public function chunkSize(): int
    {
        return 1000; // تعداد ردیف‌هایی که در هر بخش پردازش می‌شود
    }
}
