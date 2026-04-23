<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History - Rice Ordering System</title>
</head>
<body>
    <div class="navbar">
        <div>
            <a href="/dashboard">Dashboard</a>
            <a href="/menu">Menu Management</a>
            <a href="/orders/create">POS Order</a>
            <a href="/orders">Order Summary</a>
            <a href="/payments">Payments</a>
            <a href="/payments/history">Transaction History</a>
            <form method="POST" action="/logout" style="display: inline;">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <h1>Transaction History</h1>

            <form method="GET" action="/payments/history">
                <label>Search by Customer:</label>
                <input type="text" name="customer" value="{{ request('customer') }}" placeholder="Customer name or email">
                <button type="submit">Search</button>
                <a href="/payments/history">Reset</a>
            </form>

            <br>

            <table border="1">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total Amount</th>
                        <th>Payment Method</th>
                        <th>Amount Paid</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                    @foreach($transaction->payments as $payment)
                    <tr>
                        <td>{{ $payment->created_at->format('Y-m-d h:i A') }}</td>
                        <td>{{ $transaction->order_number }}</td>
                        <td>{{ $transaction->customer_name }}<br>
                            @if($transaction->customer_email)
                                <small>{{ $transaction->customer_email }}</small>
                            @endif
                        </td>
                        <td>
                            @foreach($transaction->items as $item)
                                {{ $item['name'] }} ({{ $item['quantity'] }} kg)<br>
                            @endforeach
                        </td>
                        <td>₱{{ number_format($transaction->total_amount, 2) }}</td>
                        <td>{{ $payment->payment_method }}</td>
                        <td>₱{{ number_format($payment->amount_paid, 2) }}</td>
                        <td>
                            @if($transaction->payment_status == 'Paid')
                                Fully Paid
                            @elseif($transaction->payment_status == 'Partial')
                                Partial Payment
                            @else
                                Unpaid
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>