<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Statements</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Account Statements</h1>
        <h2>{{ $account->account_type }} - {{ $account->id }}</h2>
        <p><strong>User:</strong> {{ $account->user->name }}</p>
        <p><strong>Email:</strong> {{ $account->user->email }}</p>
        <p><strong>City:</strong> {{ $account->city }}</p>
        <p><strong>Balance:</strong> ${{ $account->balance }}</p>
        <h2>Transactions for {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->transaction_date }}</td>
                        <td>{{ $transaction->fromAccount->id }}</td>
                        <td>{{ $transaction->toAccount->id }}</td>
                        <td>${{ $transaction->amount }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
