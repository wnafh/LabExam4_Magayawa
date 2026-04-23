<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold">Order Details</h1>
                        <a href="{{ route('orders.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to Orders</a>
                    </div>

                    <h3 class="text-xl font-bold mb-4">Order #: {{ $order->order_number }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <p><strong>Customer Name:</strong> {{ $order->customer_name }}</p>
                            @if($order->customer_email)
                                <p><strong>Customer Email:</strong> {{ $order->customer_email }}</p>
                            @endif
                            <p><strong>Order Status:</strong> {{ $order->order_status }}</p>
                            <p><strong>Payment Status:</strong> {{ $order->payment_status }}</p>
                        </div>
                        <div>
                            <p><strong>Total Amount:</strong> ₱{{ number_format($order->total_amount, 2) }}</p>
                            <p><strong>Amount Paid:</strong> ₱{{ number_format($order->amount_paid, 2) }}</p>
                            <p><strong>Remaining Balance:</strong> ₱{{ number_format($order->remaining_balance, 2) }}</p>
                            <p><strong>Order Date:</strong> {{ $order->created_at->format('F d, Y h:i A') }}</p>
                        </div>
                    </div>

                    <h3 class="text-xl font-bold mb-4">Order Items</h3>
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full border">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border px-4 py-2 text-left">Rice Name</th>
                                    <th class="border px-4 py-2 text-left">Category</th>
                                    <th class="border px-4 py-2 text-left">Quantity (kg)</th>
                                    <th class="border px-4 py-2 text-left">Price per kg</th>
                                    <th class="border px-4 py-2 text-left">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="border px-4 py-2">{{ $item['name'] }}</td>
                                    <td class="border px-4 py-2">{{ $item['category'] }}</td>
                                    <td class="border px-4 py-2">{{ $item['quantity'] }} kg</td>
                                    <td class="border px-4 py-2">₱{{ number_format($item['price'], 2) }}</td>
                                    <td class="border px-4 py-2">₱{{ number_format($item['subtotal'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h3 class="text-xl font-bold mb-4">Payment History</h3>
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full border">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border px-4 py-2 text-left">Date</th>
                                    <th class="border px-4 py-2 text-left">Amount Paid</th>
                                    <th class="border px-4 py-2 text-left">Payment Method</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->payments as $payment)
                                <tr>
                                    <td class="border px-4 py-2">{{ $payment->created_at->format('Y-m-d h:i A') }}</td>
                                    <td class="border px-4 py-2">₱{{ number_format($payment->amount_paid, 2) }}</td>
                                    <td class="border px-4 py-2">{{ $payment->payment_method }}</td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="border px-4 py-2 text-center">No payments yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($order->remaining_balance > 0)
                        <a href="{{ route('payments.create', $order->id) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Make Payment</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>