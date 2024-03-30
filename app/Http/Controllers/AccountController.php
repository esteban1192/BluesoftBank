<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    public function createAccountView() {
        $user = Auth::user();
        return Inertia::render('Account/CreateAccount', [
            'userEmail' => $user->email
        ]);
    }

    public function createAccount(Request $request) {
        \Log::debug($request->all());
    }
}
