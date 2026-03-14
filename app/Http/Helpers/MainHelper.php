<?php

namespace App\Http\Helpers;

class MainHelper
{
    public static function formatCurrency(int $amount): string
    {
        return number_format($amount, 0, '.', '.');
    }
}
