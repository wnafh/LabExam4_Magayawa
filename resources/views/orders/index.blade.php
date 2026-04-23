<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold">Order Summary</h1>
                        <a href="{{ route('orders.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create New Order</a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <form method="GET" action="{{ route('orders.index') }}" class="flex gap-2">
                            <label class="block text-gray-700 text-sm font-bold">Filter by Status:</label>
                            <select name="status" onchange="this.form.submit()" class="shadow border rounded py-1 px-2">
                                <option value="">All</option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border px-4 py-2 text-left">Order #</th>
                                    <th class="border px-4 py-2 text-left">Customer</th>
                                    <th class="border px-4 py-2 text-left">Items</th>
                                    <th class="border px-4 py-2 text-left">Total Amount</th>
                                    <th class="border px-4 py-2 text-left">Amount Paid</th>
                                    <th class="border px-4 py-2 text-left">Remaining Balance</th>
                                    <th class="border px-4 py-2 text-left">Order Status</th>
                                    <th class="border px-4 py-2 text-left">Payment Status</th>
                                    <th class="border px-4 py-2 text-left">Date</th>
                                    <th class="border px-4 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td class="border px-4 py-2">{{ $order->order_number }}</td>
                                    <td class="border px-4 py-2">{{ $order->customer_name }}</td>
                                    <td class="border px-4 py-2">
                                        @foreach($order->items as $item)
                                            {{ $item['name'] }} ({{ $item['quantity'] }} kg)<br>
                                        @endforeach
                                    </td>
                                    <td class="border px-4 py-2">₱{{ number_format($order->total_amount, 2) }}</td>
                                    <td class="border px-4 py-2">₱{{ number_format($order->amount_paid, 2) }}</td>
                                    <td class="border px-4 py-2">₱{{ number_format($order->remaining_balance, 2) }}</td>
                                    <td class="border px-4 py-2">
                                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('PATCH')
                                            <select name="order_status" onchange="this.form.submit()" class="shadow border rounded py-1 px-2">
                                                <option value="Pending" {{ $order->order_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="Processing" {{ $order->order_status == 'Processing' ? 'selected' : '' }}>Processing</option>
                                                <option value="Completed" {{ $order->order_status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="border px-4 py-2">
                                        @if($order->payment_status == 'Paid')
                                            <span class="bg-green-500 text-white px-2 py-1 rounded text-sm">Paid</span>
                                        @elseif($order->payment_status == 'Partial')
                                            <span class="bg-yellow-500 text-white px-2 py-1 rounded text-sm">Partial</span>
                                        @else
                                            <span class="bg-red-500 text-white px-2 py-1 rounded text-sm">Unpaid</span>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">{{ $order->created_at->format('Y-m-d') }}</td>
                                    <td class="border px-4 py-2">
                                        <a href="{{ route('orders.show', $order->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">View</a>
                                        @if($order->remaining_balance > 0)
                                            <a href="{{ route('payments.create', $order->id) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm">Pay</a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="border px-4 py-2 text-center">No orders found.</td>
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