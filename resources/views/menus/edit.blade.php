<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-4">Edit Rice Product</h1>

                    <form action="{{ route('menu.update', $menu->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Rice Name:</label>
                            <input type="text" name="name" value="{{ $menu->name }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Category:</label>
                            <select name="category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="Jasmine" {{ $menu->category == 'Jasmine' ? 'selected' : '' }}>Jasmine</option>
                                <option value="Dinorado" {{ $menu->category == 'Dinorado' ? 'selected' : '' }}>Dinorado</option>
                                <option value="Sinandomeng" {{ $menu->category == 'Sinandomeng' ? 'selected' : '' }}>Sinandomeng</option>
                                <option value="Brown Rice" {{ $menu->category == 'Brown Rice' ? 'selected' : '' }}>Brown Rice</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Price per Kilo:</label>
                            <input type="number" step="0.01" name="price_per_kilo" value="{{ $menu->price_per_kilo }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Stock (kg):</label>
                            <input type="number" step="0.1" name="stock" value="{{ $menu->stock }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                        
                        <div class="flex gap-2">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Product</button>
                            <a href="{{ route('menu.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>