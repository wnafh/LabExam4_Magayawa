<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold">Payments</h1>
                        <a href="{{ route('payments.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Make New Payment</a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full border">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border px-4 py-2 text-left">Order #</th>
                                    <th class="border px-4 py-2 text-left">Customer</th>
                                    <th class="border px-4 py-2 text-left">Total Amount</th>
                                    <th class="border px-4 py-2 text-left">Amount Paid</th>
                                    <th class="border px-4 py-2 text-left">Remaining Balance</th>
                                    <th class="border px-4 py-2 text-left">Payment Status</th>
                                    <th class="border px-4 py-2 text-left">Actions</th>
                                 </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td class="border px-4 py-2">{{ $order->order_number }}</td>
                                    <td class="border px-4 py-2">{{ $order->customer_name }}</td>
                                    <td class="border px-4 py-2">₱{{ number_format($order->total_amount, 2) }}</td>
                                    <td class="border px-4 py-2">₱{{ number_format($order->amount_paid, 2) }}</td>
                                    <td class="border px-4 py-2">₱{{ number_format($order->remaining_balance, 2) }}</td>
                                    <td class="border px-4 py-2">
                                        @if($order->payment_status == 'Paid')
                                            <span class="bg-green-500 text-white px-2 py-1 rounded text-sm">Paid</span>
                                        @elseif($order->payment_status == 'Partial')
                                            <span class="bg-yellow-500 text-white px-2 py-1 rounded text-sm">Partial</span>
                                        @else
                                            <span class="bg-red-500 text-white px-2 py-1 rounded text-sm">Unpaid</span>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">
                                        @if($order->remaining_balance > 0)
                                            <a href="{{ route('payments.create', $order->id) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm">Pay</a>
                                        @endif                                        <a href="{{ route('orders.show', $order->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">View</a>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="border px-4 py-2 text-center">No orders found.</td>
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