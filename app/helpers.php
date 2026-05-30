<?php

use Illuminate\Support\Facades\Log;

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

if (!function_exists('console_log')) {
    /**
     * Print variable to the terminal console (stderr).
     *
     * @param mixed ...$vars
     * @return void
     */
    function console_log(mixed ...$vars): void
    {
        foreach ($vars as $var) {
            $output = match (true) {
                is_string($var) => $var,
                is_array($var) || is_object($var) => json_encode($var, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                default => preg_replace('/\[([^\]]+)\] =>/', '$1:', print_r($var, true)),
            };
            Log::channel('stderr')->info($output);
        }
    }
}

