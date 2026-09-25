<?php

namespace App\Http\Controllers\Concerns;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

trait SendsPasswordResetToken
{
    /**
     * Create a broker-managed reset token (stored hashed, expiring, one per
     * user) and email the reset link. Returns false if the mail could not be sent.
     */
    protected function sendPasswordResetToken(string $email): bool
    {
        $user = User::where('email', $email)->first();

        if (! $user) {
            return false;
        }

        $token = Password::broker()->createToken($user);

        try {
            Mail::send('auth.forget-password-email', ['token' => $token], function ($message) use ($email) {
                $message->to($email);
                $message->subject('Reset password');
            });
        } catch (TransportExceptionInterface $e) {
            return false;
        }

        return true;
    }
}
