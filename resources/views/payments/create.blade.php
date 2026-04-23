<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Payment - Rice Ordering System</title>
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
            <h2>Process Payment</h2>

            @if(session('error'))
                <div style="color: red;">{{ session('error') }}</div>
            @endif

            @if(isset($orders) && !isset($order))
                <form method="GET" action="/payments/create">
                    <label>Select Order to Pay:</label>
                    <select name="order_id" required onchange="this.form.submit()">
                        <option value="">Choose order...</option>
                        @foreach($orders as $unpaidOrder)
                            <option value="{{ $unpaidOrder->id }}">
                                {{ $unpaidOrder->order_number }} - {{ $unpaidOrder->customer_name }} 
                                (Balance: ₱{{ number_format($unpaidOrder->remaining_balance, 2) }})
                            </option>
                        @endforeach
                    </select>
                </form>
            @endif
            
            @if(isset($order))
                <form action="/payments/store" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    
                    <div>
                        <h3>Order #: {{ $order->order_number }}</h3>
                        <p>Customer: {{ $order->customer_name }}</p>
                        <p>Total Amount: ₱{{ number_format($order->total_amount, 2) }}</p>
                        <p>Amount Paid: ₱{{ number_format($order->amount_paid, 2) }}</p>
                        <p><strong>Remaining Balance: ₱{{ number_format($order->remaining_balance, 2) }}</strong></p>
                    </div>

                    <label>Amount to Pay:</label>
                    <input type="number" name="amount_paid" id="amount_paid" step="0.01" min="0.01" max="{{ $order->remaining_balance }}" required><br><br>

                    <label>Change (if overpayment):</label>
                    <input type="text" id="change_display" readonly><br><br>

                    <label>Payment Method:</label>
                    <select name="payment_method" required>
                        <option value="Cash">Cash</option>
                        <option value="GCash">GCash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                    </select><br><br>
                    
                    <button type="submit">Process Payment</button>
                    <a href="/payments">Cancel</a>
                </form>
            @endif
        </div>
    </div>

    @if(isset($order))
    <script>
        const amountPaid = document.getElementById('amount_paid');
        const changeDisplay = document.getElementById('change_display');
        const remainingBalance = {{ $order->remaining_balance }};
        
        function calculateChange() {
            const paid = parseFloat(amountPaid.value) || 0;
            if (paid > remainingBalance) {
                const change = paid - remainingBalance;
                changeDisplay.value = 'Overpayment: ₱' + change.toFixed(2);
            } else {
                changeDisplay.value = '₱0.00';
            }
        }
        
        amountPaid.addEventListener('input', calculateChange);
    </script>
    @endif
</body>
</html>