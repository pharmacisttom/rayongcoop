<?php

declare(strict_types=1);

namespace App\Services;

class TwoFactorService
{
    private const BASE32_CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    public static function generateSecret(int $length = 16): string
    {
        $secret = '';
        for ($i = 0; $i < $length; $i++) {
            $secret .= self::BASE32_CHARS[random_int(0, 31)];
        }
        return $secret;
    }

    public static function verifyCode(string $secret, string $code, int $discrepancy = 1): bool
    {
        $currentTimeSlice = (int) floor(time() / 30);
        $code = trim($code);

        for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
            $calculatedCode = self::calculateCode($secret, $currentTimeSlice + $i);
            if (hash_equals($calculatedCode, $code)) {
                return true;
            }
        }
        return false;
    }

    public static function getQrCodeUrl(string $email, string $secret, ?string $issuer = null): string
    {
        $issuer = $issuer ?: config('security.two_factor.issuer', 'RayongCoop');
        $otpAuthUrl = sprintf(
            'otpauth://totp/%s:%s?secret=%s&issuer=%s&algorithm=SHA1&digits=6&period=30',
            rawurlencode($issuer),
            rawurlencode($email),
            $secret,
            rawurlencode($issuer)
        );

        return 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' . rawurlencode($otpAuthUrl);
    }

    private static function calculateCode(string $secret, int $timeSlice): string
    {
        $secretKey = self::base32Decode($secret);
        $time = chr(0) . chr(0) . chr(0) . chr(0) . pack('N*', $timeSlice);
        $hmac = hash_hmac('sha1', $time, $secretKey, true);
        $offset = ord(substr($hmac, -1)) & 0x0F;
        $hashPart = substr($hmac, $offset, 4);
        $value = unpack('N', $hashPart)[1] & 0x7FFFFFFF;
        $modulo = 10 ** 6;
        return str_pad((string) ($value % $modulo), 6, '0', STR_PAD_LEFT);
    }

    private static function base32Decode(string $b32): string
    {
        $b32 = strtoupper($b32);
        $buffer = 0;
        $length = 0;
        $binary = '';

        for ($i = 0; $i < strlen($b32); $i++) {
            $position = strpos(self::BASE32_CHARS, $b32[$i]);
            if ($position === false) {
                continue;
            }
            $buffer = ($buffer << 5) | $position;
            $length += 5;

            if ($length >= 8) {
                $length -= 8;
                $binary .= chr(($buffer >> $length) & 0xFF);
            }
        }

        return $binary;
    }
}
