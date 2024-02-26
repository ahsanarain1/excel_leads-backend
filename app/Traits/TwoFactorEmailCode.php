<?php

namespace App\Traits;

use App\Models\VerificationCode;

trait TwoFactorEmailCode
{
    public function generateVerificationCode(): VerificationCode
    {
        // Generate a new verification code
        $verificationCode = rand(100000, 999999);

        // Store the verification code and its expiration time in the database
        return VerificationCode::create([
            'user_id' => $this->id,
            'code' => $verificationCode,
            'expires_at' => now()->addMinutes(5),
        ]);
    }
    public function verificationCodes()
    {
        return $this->hasMany(VerificationCode::class);
    }
}
