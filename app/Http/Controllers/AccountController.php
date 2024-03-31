<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Rules\UserOwnsAccount;

class AccountController extends Controller
{
    public function createAccountView() {
        $user = Auth::user();
        return Inertia::render('Account/CreateAccount', [
            'userEmail' => $user->email
        ]);
    }
    
    public function createAccount(Request $request) {
        try {
            // Validate request data
            $validatedData = $request->validate([
                'accountType' => 'required|string|in:checking,savings',
                'balance' => 'required|numeric|min:0',
                'city' => 'nullable|string', // Add validation for the 'city' field
            ]);
    
            $userId = auth()->user()->id;
    
            DB::beginTransaction();
    
            // Create the account
            $account = new Account();
            $account->user_id = $userId;
            $account->account_type = $validatedData['accountType'];
            $account->balance = $validatedData['balance'];
            
            // Add city data to the account if provided
            if (isset($validatedData['city'])) {
                $account->city = $validatedData['city'];
            }
            
            $account->save();
            
            DB::commit();
            
            return redirect('/dashboard')->with('success', 'Account created successfully');
        } catch (QueryException $e) {
            DB::rollBack();
            \Log::error('Failed to create account: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create account. Please try again later.'], 500);
        }  catch (\Exception $e) {
            DB::rollBack();
            \Log::error('An unexpected error occurred: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred. Please try again later.'], 500);
        }
    }

    public function generateStatements(Request $request)
    {
        $user = Auth::user();
        $accountId = $request->query('accountId');
        $month = $request->query('month'); 

        $data = [
            'accountId' => (int)$accountId,
            'month' => $month,
            'userEmail' => $user->email
        ];

        return Inertia::render('Account/GenerateStatementsForm', $data);
    }

    public function pdfStatements(Request $request)
    {
        try {
            // Retrieve query parameters
            $accountId = $request->query('accountId');
            $month = $request->query('month');
            $year = $request->query('year');
            
            // Get the currently authenticated user
            $user = Auth::user();
            
            // Fetch account details
            $account = Account::find($accountId);

            // Validate if the user owns the specified account
            if ($account->user_id !== $user->id) {
                throw new \Exception("User does not own the specified account.");
            }
            
            // Fetch transactions for the specified month and year
            $transactions = Transaction::where(function($query) use ($accountId, $user) {
                    $query->where(function($subQuery) use ($accountId) {
                        $subQuery->where('from_account_id', $accountId)
                                ->orWhere('to_account_id', $accountId);
                    });
                })
                ->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $month)
                ->get();
            
            // Prepare HTML content for the PDF
            $data = [
                'account' => $account,
                'transactions' => $transactions,
                'month' => $month,
                'year' => $year
            ];
            
            $pdf = Pdf::loadView('pdf.statements', $data);
            \Log::debug("hey");
            return $pdf->download('statements.pdf');
        } catch (\Exception $e) {
            \Log::error('Error generating PDF statements: ' . $e->getMessage());

            $errorData = [
                'error' => 'Failed to generate PDF statements. Please try again later.',
            ];

            return Inertia::render('Dashboard', $errorData);
        }
    }
}
