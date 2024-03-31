<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Inertia\Inertia;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function usersListView(Request $request)
    {
        // Fetch the filters from the request parameters
        $month = $request->input('month', null);
        $outOfCityOnly = $request->input('outOfCityOnly', null);
        $moreThanOneMillionOnly = $request->input('moreThanOneMillionOnly', null);

        // Initialize a query builder for the User model
        $query = User::query();

        // If month is provided, filter users by the given month and year
        if ($month) {
            $startDate = Carbon::parse($month)->startOfMonth();
            $endDate = Carbon::parse($month)->endOfMonth();

            // Filter users by the transactions within the given month and year
            $query->whereHas('transactions', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('transaction_date', [$startDate, $endDate]);
            });
        }

        // If moreThanOneMillionOnly is true, filter users who have more than 1,000,000 transactions
        if ($moreThanOneMillionOnly === 'true') {
            $query->whereHas('transactions', function ($query) {
                $query->where('amount', '>=', 1000000);
            });
        }

        $users = $query->withCount('transactions')->orderByDesc('transactions_count')->get();

        // Pass the filtered and sorted list of users to the component
        return Inertia::render('Account/UsersListView', [
            'userList' => $users,
            'user' => Auth::user(),
        ]);
    }
}
