<?php

if (!function_exists('format_vnd')) {
    function format_vnd($amount): string
    {
        $amount = $amount ?? 0;
        if (extension_loaded('intl')) {
            $fmt = new \NumberFormatter('vi_VN', \NumberFormatter::CURRENCY);
            $fmt->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
            $result = $fmt->formatCurrency((float)$amount, 'VND');
            return $result;
        }

        return number_format((float)$amount, 0, ',', '.') . ' ₫';
    }
}
