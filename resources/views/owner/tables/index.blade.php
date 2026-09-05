@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Manage Tables & QR Codes</h1>
            <p class="text-gray-600">Create tables and generate unique QR codes for your restaurant.</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('owner.tables.print') }}" class="bg-orange-600 text-white px-4 py-2 rounded-md text-sm font-bold hover:bg-orange-700">Download QR PDF</a>
            <a href="{{ route('owner.dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-orange-600 py-2">Back to Dashboard</a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-8 bg-green-50 border-l-4 border-green-400 p-4">
            <p class="text-sm text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Add Table Form -->
        <div class="bg-white p-6 shadow-sm rounded-lg border border-gray-100">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Table</h3>
            <form action="{{ route('owner.tables.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="table_number" class="block text-sm font-medium text-gray-700">Table Name/Number</label>
                        <input type="text" name="table_number" id="table_number" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 sm:text-sm" placeholder="e.g. Table 12">
                    </div>
                    <button type="submit" class="w-full bg-orange-600 text-white py-2 px-4 rounded-md text-sm font-bold hover:bg-orange-700">Add Table</button>
                </div>
            </form>
        </div>

        <!-- Tables List -->
        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @forelse($tables as $table)
                @php
                    $tableUrl = $restaurant->getTableUrl($table->id);
                @endphp
                <div class="bg-white p-6 shadow-sm rounded-lg border border-gray-100 flex flex-col items-center text-center">
                    <div class="bg-gray-100 p-4 rounded-lg mb-4">
                        {!! QrCode::size(100)->generate($tableUrl) !!}
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $table->table_number }}</h3>
                    <p class="text-[10px] text-gray-400 mb-2 truncate w-full">{{ $tableUrl }}</p>
                    
                    <a href="{{ $tableUrl }}" target="_blank" class="text-xs font-bold text-orange-600 hover:text-orange-700 mb-4 inline-flex items-center">
                        View Menu
                        <svg class="ml-1 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>

                    <form action="{{ route('owner.tables.destroy', $table) }}" method="POST" onsubmit="return confirm('Delete this table?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-900 uppercase">Delete</button>
                    </form>
                </div>
            @empty
                <div class="col-span-full py-20 bg-white rounded-lg border border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-500">
                    <p>No tables created yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
