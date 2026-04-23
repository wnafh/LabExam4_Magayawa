<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
                    <p class="mb-4">Welcome, {{ Auth::user()->name }}!</p>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                        <div class="bg-blue-100 p-4 rounded">
                            <h3 class="font-bold">Total Menu Items</h3>
                            <p class="text-2xl">{{ $totalMenuItems }}</p>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded">
                            <h3 class="font-bold">Pending Orders</h3>
                            <p class="text-2xl">{{ $pendingOrders }}</p>
                        </div>
                        <div class="bg-purple-100 p-4 rounded">
                            <h3 class="font-bold">Processing Orders</h3>
                            <p class="text-2xl">{{ $processingOrders }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded">
                            <h3 class="font-bold">Completed Orders</h3>
                            <p class="text-2xl">{{ $completedOrders }}</p>
                        </div>
                        <div class="bg-indigo-100 p-4 rounded">
                            <h3 class="font-bold">Total Revenue</h3>
                            <p class="text-2xl">₱{{ number_format($totalRevenue, 2) }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold mb-4">Recent Orders</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full border">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="border px-4 py-2 text-left">Order #</th>
                                        <th class="border px-4 py-2 text-left">Customer</th>
                                        <th class="border px-4 py-2 text-left">Total</th>
                                        <th class="border px-4 py-2 text-left">Status</th>
                                        <th class="border px-4 py-2 text-left">Payment Status</th>
                                        <th class="border px-4 py-2 text-left">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $order->order_number }}</td>
                                        <td class="border px-4 py-2">{{ $order->customer_name }}</td>
                                        <td class="border px-4 py-2">₱{{ number_format($order->total_amount, 2) }}</td>
                                        <td class="border px-4 py-2">{{ $order->order_status }}</td>
                                        <td class="border px-4 py-2">{{ $order->payment_status }}</td>
                                        <td class="border px-4 py-2">{{ $order->created_at->format('Y-m-d') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>