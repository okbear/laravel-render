<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminAccess
{
    public static function isConfigured(): bool
    {
        return count(config('admin.emails', [])) > 0;
    }

    public static function emailIsAllowed(?string $email): bool
    {
        if (! $email || ! self::isConfigured()) {
            return false;
        }

        return in_array(strtolower($email), config('admin.emails', []), true);
    }

    public static function allows(?User $user = null): bool
    {
        $user ??= Auth::user();

        return $user instanceof User && self::emailIsAllowed($user->email);
    }
}
