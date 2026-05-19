@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Manage Categories</h1>
            <p class="text-gray-600">Organize your menu into sections like Starters, Main Dishes, etc.</p>
        </div>
        <a href="{{ route('owner.dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-orange-600">Back to Dashboard</a>
    </div>

    @if (session('success'))
        <div class="mb-8 bg-green-50 border-l-4 border-green-400 p-4">
            <p class="text-sm text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Add Category Form -->
        <div class="bg-white p-6 shadow-sm rounded-lg border border-gray-100">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Category</h3>
            <form action="{{ route('owner.categories.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Category Name</label>
                        <input type="text" name="name" id="name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 sm:text-sm" placeholder="e.g. Starters">
                    </div>
                    <button type="submit" class="w-full bg-orange-600 text-white py-2 px-4 rounded-md text-sm font-bold hover:bg-orange-700">Add Category</button>
                </div>
            </form>
        </div>

        <!-- Categories List -->
        <div class="lg:col-span-2 bg-white shadow-sm rounded-lg border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($categories as $category)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $category->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $category->items()->count() }} items
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end space-x-2">
                                <form action="{{ route('owner.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure? This will delete all items in this category.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-500">
                                No categories created yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
