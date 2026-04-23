<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary - Rice Ordering System</title>
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
            <h1>Order Summary</h1>

            @if(session('success'))
                <div style="color: green;">{{ session('success') }}</div>
            @endif

            <form method="GET" action="/orders">
                <label>Filter by Status:</label>
                <select name="status" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </form>

            <br>

            <table border="1">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total Amount</th>
                        <th>Amount Paid</th>
                        <th>Remaining Balance</th>
                        <th>Order Status</th>
                        <th>Payment Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>
                            @foreach($order->items as $item)
                                {{ $item['name'] }} ({{ $item['quantity'] }} kg)<br>
                            @endforeach
                        </td>
                        <td>₱{{ number_format($order->total_amount, 2) }}</td>
                        <td>₱{{ number_format($order->amount_paid, 2) }}</td>
                        <td>₱{{ number_format($order->remaining_balance, 2) }}</td>
                        <td>
                            <form action="/orders/{{ $order->id }}/status" method="POST" style="display:inline">
                                @csrf
                                @method('PATCH')
                                <select name="order_status" onchange="this.form.submit()">
                                    <option value="Pending" {{ $order->order_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Processing" {{ $order->order_status == 'Processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="Completed" {{ $order->order_status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            @if($order->payment_status == 'Paid')
                                Paid
                            @elseif($order->payment_status == 'Partial')
                                Partial
                            @else
                                Unpaid
                            @endif
                        </td>
                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="/orders/{{ $order->id }}">View</a>
                            @if($order->remaining_balance > 0)
                                <a href="/payments/create/{{ $order->id }}">Pay</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>