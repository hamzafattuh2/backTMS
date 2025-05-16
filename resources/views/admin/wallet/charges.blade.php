<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Wallet Charges</title>
</head>
<body>
    <h2>Pending Wallet Charges</h2>
    @if(session('success'))
        <div style="color:green;">{{ session('success') }}</div>
    @endif
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->wallet && $transaction->wallet->user ? $transaction->wallet->user->user_name : '' }}</td>
                    <td>{{ $transaction->amount }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td>{{ $transaction->created_at }}</td>
                    <td>
                        <form action="{{ route('admin.wallet.charges.confirm', $transaction) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit">Confirm</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 