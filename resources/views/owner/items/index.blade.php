@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Manage Menu Items</h1>
            <p class="text-gray-600">Add dishes, drinks, and snacks to your digital menu.</p>
        </div>
        <a href="{{ route('owner.dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-orange-600">Back to Dashboard</a>
    </div>

    @if (session('success'))
        <div class="mb-8 bg-green-50 border-l-4 border-green-400 p-4">
            <p class="text-sm text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Add/Filter Sidebar -->
        <div class="space-y-8">
            <div class="bg-white p-6 shadow-sm rounded-lg border border-gray-100">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Item</h3>
                <form action="{{ route('owner.items.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category_id" id="category_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Item Name</label>
                            <input type="text" name="name" id="name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Price (FCFA)</label>
                            <input type="number" name="price" id="price" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 sm:text-sm"></textarea>
                        </div>
                        <div>
                            <label for="photo" class="block text-sm font-medium text-gray-700">Photo</label>
                            <input type="file" name="photo" id="photo" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                        </div>
                        <button type="submit" class="w-full bg-orange-600 text-white py-2 px-4 rounded-md text-sm font-bold hover:bg-orange-700">Save Item</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Items Grid -->
        <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($items as $item)
                <div class="bg-white shadow-sm rounded-lg border border-gray-100 overflow-hidden flex flex-col">
                    <div class="h-48 w-full bg-gray-200 relative">
                        @if($item->photo_path)
                            <img src="{{ Storage::url($item->photo_path) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-400">
                                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-1 {{ $item->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-xs font-bold rounded-full shadow-sm">
                                {{ $item->is_available ? 'Available' : 'Sold Out' }}
                            </span>
                        </div>
                    </div>
                    <div class="p-4 flex-grow">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-gray-900">{{ $item->name }}</h3>
                            <span class="text-orange-600 font-bold">{{ number_format($item->price, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <p class="text-xs text-gray-500 mb-2 uppercase tracking-wide">{{ $item->category->name }}</p>
                        <p class="text-sm text-gray-600 line-clamp-2">{{ $item->description }}</p>
                    </div>
                    <div class="p-4 border-t border-gray-50 flex justify-between items-center bg-gray-50">
                        <form action="{{ route('owner.items.toggle-availability', $item) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs font-bold {{ $item->is_available ? 'text-gray-500 hover:text-red-600' : 'text-green-600 hover:text-green-800' }}">
                                {{ $item->is_available ? 'Mark Sold Out' : 'Mark Available' }}
                            </button>
                        </form>
                        <form action="{{ route('owner.items.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this item?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 bg-white rounded-lg border border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-500">
                    <p>No items in your menu yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
