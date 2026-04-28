<?php

namespace App\Services;

use App\Models\RecoveryCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RecoveryCodeService
{
    public static function regenerateFor(User $user, int $count = 8): array
    {
        RecoveryCode::where('user_id', $user->id)->delete();

        $plainCodes = [];

        for ($i = 0; $i < $count; $i++) {
            $plainCode = strtoupper(Str::random(4) . '-' . Str::random(4));
            $plainCodes[] = $plainCode;

            RecoveryCode::create([
                'user_id'   => $user->id,
                'code_hash' => Hash::make($plainCode),
            ]);
        }

        return $plainCodes;
    }
}