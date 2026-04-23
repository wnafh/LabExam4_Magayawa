<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-4">Point of Sale - Create Order</h1>

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column: Add Items -->
                        <div>
                            <h3 class="text-xl font-bold mb-4">Select Rice Item</h3>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Rice Product:</label>
                                <select id="menu_id" class="shadow border rounded w-full py-2 px-3" required>
                                    <option value="">Choose rice...</option>
                                    @foreach($menus as $menu)
                                        <option value="{{ $menu->id }}" data-price="{{ $menu->price_per_kilo }}" data-stock="{{ $menu->stock }}">
                                            {{ $menu->name }} ({{ $menu->category }}) - ₱{{ number_format($menu->price_per_kilo, 2) }}/kg (Stock: {{ $menu->stock }} kg)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Quantity (kg):</label>
                                <input type="number" id="quantity" step="0.1" min="0.1" class="shadow border rounded w-full py-2 px-3">
                            </div>

                            <button type="button" onclick="addToCart()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add to Order</button>
                        </div>

                        <!-- Right Column: Current Order -->
                        <div>
                            <h3 class="text-xl font-bold mb-4">Current Order</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full border mb-4">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border px-2 py-1 text-left">Rice</th>
                                            <th class="border px-2 py-1 text-left">Qty</th>
                                            <th class="border px-2 py-1 text-left">Price</th>
                                            <th class="border px-2 py-1 text-left">Subtotal</th>
                                            <th class="border px-2 py-1 text-left">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cartBody"></tbody>
                                    <tfoot>
                                        <tr class="bg-gray-100 font-bold">
                                            <td colspan="3" class="border px-2 py-2 text-right">Total:</td>
                                            <td colspan="2" class="border px-2 py-2" id="totalAmount">₱0.00</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="mt-6">
                        <h3 class="text-xl font-bold mb-4">Customer Information</h3>
                        <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
                            @csrf
                            <input type="hidden" name="cart_data" id="cart_data">
                            <input type="hidden" name="total_amount" id="hiddenTotal">

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Customer Name:</label>
                                <input type="text" name="customer_name" class="shadow border rounded w-full py-2 px-3" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Customer Email (optional):</label>
                                <input type="email" name="customer_email" class="shadow border rounded w-full py-2 px-3">
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Order Status:</label>
                                <select name="order_status" class="shadow border rounded w-full py-2 px-3" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Processing">Processing</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Place Order</button>
                                <a href="{{ route('orders.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let cart = [];

        function addToCart() {
            const menuSelect = document.getElementById('menu_id');
            const selectedOption = menuSelect.options[menuSelect.selectedIndex];
            const menuId = menuSelect.value;
            const menuName = selectedOption.text.split(' - ')[0];
            const category = selectedOption.text.includes('Jasmine') ? 'Jasmine' : 
                            (selectedOption.text.includes('Dinorado') ? 'Dinorado' :
                            (selectedOption.text.includes('Sinandomeng') ? 'Sinandomeng' : 'Brown Rice'));
            const price = parseFloat(selectedOption.dataset.price);
            const quantity = parseFloat(document.getElementById('quantity').value);
            const stock = parseFloat(selectedOption.dataset.stock);

            if (!menuId) {
                alert('Please select a rice product');
                return;
            }

            if (!quantity || quantity <= 0) {
                alert('Please enter a valid quantity');
                return;
            }

            if (quantity > stock) {
                alert('Insufficient stock! Available: ' + stock + ' kg');
                return;
            }

            const existingItem = cart.find(item => item.menu_id == menuId);
            if (existingItem) {
                existingItem.quantity += quantity;
                existingItem.subtotal = existingItem.quantity * existingItem.price;
            } else {
                cart.push({
                    menu_id: menuId,
                    name: menuName,
                    category: category,
                    quantity: quantity,
                    price: price,
                    subtotal: quantity * price
                });
            }

            document.getElementById('quantity').value = '';
            menuSelect.value = '';
            updateCartDisplay();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCartDisplay();
        }

        function updateCartDisplay() {
            const cartBody = document.getElementById('cartBody');
            cartBody.innerHTML = '';
            let total = 0;

            cart.forEach((item, index) => {
                total += item.subtotal;
                const row = cartBody.insertRow();
                row.insertCell(0).innerHTML = item.name;
                row.insertCell(1).innerHTML = item.quantity + ' kg';
                row.insertCell(2).innerHTML = '₱' + item.price.toFixed(2);
                row.insertCell(3).innerHTML = '₱' + item.subtotal.toFixed(2);
                row.insertCell(4).innerHTML = '<button type="button" onclick="removeFromCart(' + index + ')" class="bg-red-500 text-white px-2 py-1 rounded text-sm">Remove</button>';
            });

            document.getElementById('totalAmount').innerHTML = '₱' + total.toFixed(2);
            document.getElementById('hiddenTotal').value = total;
            document.getElementById('cart_data').value = JSON.stringify(cart);
        }

        document.getElementById('orderForm').addEventListener('submit', function(e) {
            if (cart.length === 0) {
                e.preventDefault();
                alert('Please add items to the order');
            }
        });
    </script>
</x-app-layout>