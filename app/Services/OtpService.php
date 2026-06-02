<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class OtpService
{
    private const OTP_TTL_MINUTES = 5;

    public function send(string $purpose, string $identifier, callable $mailer, mixed $payload = null): string
    {
        if ($payload !== null) {
            Cache::put($this->payloadKey($purpose, $identifier), $payload, self::OTP_TTL_MINUTES * 60);
        }

        $otp = (string) random_int(100000, 999999);
        Cache::put($this->otpKey($purpose, $identifier), $otp, self::OTP_TTL_MINUTES * 60);

        $mailer($otp);

        return $otp;
    }

    public function verify(string $purpose, string $identifier, string $otp): bool
    {
        return Cache::get($this->otpKey($purpose, $identifier)) === $otp;
    }

    public function getPayload(string $purpose, string $identifier): mixed
    {
        return Cache::get($this->payloadKey($purpose, $identifier));
    }

    public function forget(string $purpose, string $identifier): void
    {
        Cache::forget($this->payloadKey($purpose, $identifier));
        Cache::forget($this->otpKey($purpose, $identifier));
    }

    private function payloadKey(string $purpose, string $identifier): string
    {
        return $purpose . '_otp_payload_' . $identifier;
    }

    private function otpKey(string $purpose, string $identifier): string
    {
        return $purpose . '_otp_code_' . $identifier;
    }
}