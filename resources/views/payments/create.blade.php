<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-4">Process Payment</h1>

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(isset($orders) && !isset($order))
                        <form method="GET" action="{{ route('payments.create') }}">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Select Order to Pay:</label>
                                <select name="order_id" class="shadow border rounded w-full py-2 px-3" required onchange="this.form.submit()">
                                    <option value="">Choose order...</option>
                                    @foreach($orders as $unpaidOrder)
                                        <option value="{{ $unpaidOrder->id }}">
                                            {{ $unpaidOrder->order_number }} - {{ $unpaidOrder->customer_name }} 
                                            (Balance: ₱{{ number_format($unpaidOrder->remaining_balance, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    @endif
                    
                    @if(isset($order))
                        <form action="{{ route('payments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            
                            <div class="bg-gray-100 p-4 rounded mb-4">
                                <h3 class="text-xl font-bold mb-2">Order #: {{ $order->order_number }}</h3>
                                <p>Customer: {{ $order->customer_name }}</p>
                                <p>Total Amount: ₱{{ number_format($order->total_amount, 2) }}</p>
                                <p>Amount Paid: ₱{{ number_format($order->amount_paid, 2) }}</p>
                                <p><strong>Remaining Balance: ₱{{ number_format($order->remaining_balance, 2) }}</strong></p>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Amount to Pay:</label>
                                <input type="number" name="amount_paid" id="amount_paid" step="0.01" min="0.01" max="{{ $order->remaining_balance }}" class="shadow border rounded w-full py-2 px-3" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Change/Overpayment:</label>
                                <input type="text" id="change_display" class="shadow border rounded w-full py-2 px-3 bg-gray-100" readonly>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Payment Method:</label>
                                <select name="payment_method" class="shadow border rounded w-full py-2 px-3" required>
                                    <option value="Cash">Cash</option>
                                    <option value="GCash">GCash</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                </select>
                            </div>
                            
                            <div class="flex gap-2">
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Process Payment</button>
                                <a href="{{ route('payments.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
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
</x-app-layout>