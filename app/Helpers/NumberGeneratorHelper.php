<?php

namespace App\Helpers;

use App\Models\Hr\Loan;
use Carbon\Carbon;

class NumberGeneratorHelper
{
    public static function loan_number()
    {
        $year  = Carbon::now()->year;
        $month = Carbon::now()->month;

        // Format prefix
        $prefix = "WCK/WL/{$year}/{$month}.";

        // Cari nomor terakhir dari database
        $last = Loan::where('loan_number', 'like', $prefix . '%')
            ->orderBy('loan_number', 'desc')
            ->value('loan_number');

        // Tentukan sequence berikutnya
        if ($last) {
            // Ambil bagian setelah titik
            $lastSeq = (int) substr(strrchr($last, "."), 1);
            $nextSeq = $lastSeq + 1;
        } else {
            $nextSeq = 1;
        }

        // Padding minimal 3 digit (001, 051), jika lebih besar tetap jalan (1023)
        $seq = str_pad($nextSeq, 3, '0', STR_PAD_LEFT);

        return $prefix . $seq;
    }
}
