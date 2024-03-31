<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Models\Transaction;
use App\Rules\UserOwnsAccount;

class TransactionController extends Controller
{
    public function makeTransactionView() {
        $user = Auth::user();
        return Inertia::render('Transaction/MakeTransaction', [
            'user' => $user,
            'accounts' => $user->accounts,
        ]);
    }

    public function makeTransaction(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'fromAccount' => ['required', 'exists:accounts,id', new UserOwnsAccount],
                'toAccount' => ['required', 'exists:accounts,id', 'different:fromAccount'],
                'amount' => ['required', 'numeric', 'min:0'],
            ]);
    
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }
    
            $userId = auth()->user()->id;
    
            DB::beginTransaction();
    
            $fromAccount = Account::where('id', $request->fromAccount)->lockForUpdate()->first();
            $toAccount = Account::where('id', $request->toAccount)->lockForUpdate()->first();
    
            if ($fromAccount->balance < $request->amount) {
                DB::rollBack();
                return Redirect::back()->withErrors(['amount' => 'Insufficient balance.'])->withInput();
            }
    
            $fromAccount->balance -= $request->amount;
            $fromAccount->save();
            $toAccount->balance += $request->amount;
            $toAccount->save();
    
            $transaction = new Transaction();
            $transaction->user_id = $userId;
            $transaction->from_account_id = $request->fromAccount;
            $transaction->to_account_id = $request->toAccount;
            $transaction->amount = $request->amount;
            $transaction->save();
    
            DB::commit();
    
            return Redirect::route('dashboard')->with('success', 'Transaction created successfully.');
    
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating transaction: ' . $e->getMessage());
    
            $errorData = [
                'error' => 'Failed to create transaction. Please try again later.',
            ];
    
            return Inertia::render('Dashboard', $errorData);
        }
    }

    public function recentTransactionsView(Request $request) {
        try {
            // Get the logged-in user
            $user = Auth::user();
    
            // Start building the query to retrieve user's transactions
            $query = $user->transactions()->latest();
    
            // Filter by account ID if provided
            if ($request->has('accountId')) {
                $query->where('from_account_id', $request->accountId)
                      ->orWhere('to_account_id', $request->accountId);
            }
    
            // Filter by minimum amount if provided
            if ($request->has('minAmount')) {
                $query->where('amount', '>=', $request->minAmount);
            }
    
            // Filter by maximum amount if provided
            if ($request->has('maxAmount')) {
                $query->where('amount', '<=', $request->maxAmount);
            }
    
            // Retrieve the latest 10 transactions matching the filters
            $transactions = $query->limit(10)->get();
    
            // Prepare the data to be passed to the Inertia view
            $data = [
                'transactions' => $transactions,
                'user' => $user
            ];
    
            // Return the Inertia view with the transaction data
            return Inertia::render('Transaction/RecentTransactions', $data);
    
        } catch (\Exception $e) {
            // If an error occurs, log the error and return an error response
            \Log::error('Error fetching recent transactions: ' . $e->getMessage());
            return Inertia::render('Error', ['error' => 'Failed to fetch recent transactions.']);
        }
    }
}
