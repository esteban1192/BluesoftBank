<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Account;

class UserOwnsAccount implements Rule
{
    public function passes($attribute, $value)
    {
        $userId = auth()->id();
        $account = Account::where('id', $value)->where('user_id', $userId)->first();
        return $account !== null;
    }

    public function message()
    {
        return 'The user does not own the specified account.';
    }
}
