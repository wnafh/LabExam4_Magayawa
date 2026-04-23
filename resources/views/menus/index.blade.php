<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold">Menu Management</h1>
                        <a href="{{ route('menu.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add New Rice</a>
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
                                    <th class="border px-4 py-2 text-left">ID</th>
                                    <th class="border px-4 py-2 text-left">Name</th>
                                    <th class="border px-4 py-2 text-left">Category</th>
                                    <th class="border px-4 py-2 text-left">Price per Kilo</th>
                                    <th class="border px-4 py-2 text-left">Stock (kg)</th>
                                    <th class="border px-4 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($menus as $menu)
                                <tr>
                                    <td class="border px-4 py-2">{{ $menu->id }}</td>
                                    <td class="border px-4 py-2">{{ $menu->name }}</td>
                                    <td class="border px-4 py-2">{{ $menu->category }}</td>
                                    <td class="border px-4 py-2">₱{{ number_format($menu->price_per_kilo, 2) }}</td>
                                    <td class="border px-4 py-2">{{ $menu->stock }} kg</td>
                                    <td class="border px-4 py-2">
                                        <a href="{{ route('menu.edit', $menu->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded text-sm">Edit</a>
                                        <form action="{{ route('menu.destroy', $menu->id) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm" onclick="return confirm('Delete this product?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="border px-4 py-2 text-center">No menu items found.</td>
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