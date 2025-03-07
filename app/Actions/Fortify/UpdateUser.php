<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UpdateUser
{
    use PasswordValidationRules;

    /**
     * Validate and update the given user's account.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:120'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_admin' => ['nullable', 'boolean'],
        ])->validate();
        (new UpdateUserProfileInformation)->update($user, Arr::except($input, ['password', 'is_admin']));
        if (isset($input['password'])) {
            $user->password = Hash::make($input['password']);
        }
        $user->is_admin = $input['is_admin'];
        $user->save();
    }
}
