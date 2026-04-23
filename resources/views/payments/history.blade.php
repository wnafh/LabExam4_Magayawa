<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-4">Transaction History</h1>

                    <div class="mb-4">
                        <form method="GET" action="{{ route('payments.history') }}" class="flex gap-2 items-end">
                            <div class="flex-1">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Search by Customer:</label>
                                <input type="text" name="customer" value="{{ request('customer') }}" placeholder="Customer name or email" class="shadow border rounded w-full py-2 px-3">
                            </div>
                            <div>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Search</button>
                                <a href="{{ route('payments.history') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Reset</a>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border px-4 py-2 text-left">Date</th>
                                    <th class="border px-4 py-2 text-left">Order #</th>
                                    <th class="border px-4 py-2 text-left">Customer</th>
                                    <th class="border px-4 py-2 text-left">Items</th>
                                    <th class="border px-4 py-2 text-left">Total Amount</th>
                                    <th class="border px-4 py-2 text-left">Payment Method</th>
                                    <th class="border px-4 py-2 text-left">Amount Paid</th>
                                    <th class="border px-4 py-2 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    @foreach($transaction->payments as $payment)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $payment->created_at->format('Y-m-d h:i A') }}</td>
                                        <td class="border px-4 py-2">{{ $transaction->order_number }}</td>
                                        <td class="border px-4 py-2">
                                            {{ $transaction->customer_name }}<br>
                                            @if($transaction->customer_email)
                                                <small class="text-gray-500">{{ $transaction->customer_email }}</small>
                                            @endif
                                        </td>
                                        <td class="border px-4 py-2">
                                            @foreach($transaction->items as $item)
                                                {{ $item['name'] }} ({{ $item['quantity'] }} kg)<br>
                                            @endforeach
                                        </td>
                                        <td class="border px-4 py-2">₱{{ number_format($transaction->total_amount, 2) }}</td>
                                        <td class="border px-4 py-2">{{ $payment->payment_method }}</td>
                                        <td class="border px-4 py-2">₱{{ number_format($payment->amount_paid, 2) }}</td>
                                        <td class="border px-4 py-2">
                                            @if($transaction->payment_status == 'Paid')
                                                <span class="bg-green-500 text-white px-2 py-1 rounded text-sm">Fully Paid</span>
                                            @elseif($transaction->payment_status == 'Partial')
                                                <span class="bg-yellow-500 text-white px-2 py-1 rounded text-sm">Partial Payment</span>
                                            @else
                                                <span class="bg-red-500 text-white px-2 py-1 rounded text-sm">Unpaid</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="8" class="border px-4 py-2 text-center">No transactions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>