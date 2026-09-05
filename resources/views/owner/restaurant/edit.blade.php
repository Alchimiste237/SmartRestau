@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">Edit Restaurant Profile</h1>
            <p class="text-gray-600">Update your restaurant details and branding.</p>
        </div>
        <a href="{{ route('owner.dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-orange-600">Back to Dashboard</a>
    </div>

    @if (session('success'))
        <div class="mb-8 bg-green-50 border-l-4 border-green-400 p-4">
            <p class="text-sm text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
        <form action="{{ route('owner.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column: Info -->
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Restaurant Name</label>
                        <input type="text" name="name" id="name" value="{{ $restaurant->name }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="business_type" class="block text-sm font-medium text-gray-700">Business Type</label>
                        <input type="text" name="business_type" id="business_type" value="{{ $restaurant->business_type }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <input type="text" name="location" id="location" value="{{ $restaurant->location }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="contact" class="block text-sm font-medium text-gray-700">Contact Number</label>
                        <input type="text" name="contact" id="contact" value="{{ $restaurant->contact }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <label for="local_network_url" class="block text-sm font-bold text-orange-600 mb-1">Offline LAN Support</label>
                        <p class="text-xs text-gray-500 mb-2">If you are running this software on a local network, enter the local URL (e.g. <code>http://192.168.1.5:8000</code>). This will be used for QR codes.</p>
                        <input type="text" name="local_network_url" id="local_network_url" value="{{ $restaurant->local_network_url }}" placeholder="http://192.168.1.5:8000" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                    </div>
                </div>

                <!-- Right Column: Images -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Restaurant Logo</label>
                        <div class="flex items-center space-x-6">
                            <div class="h-24 w-24 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                @if($restaurant->logo_path)
                                    <img src="{{ Storage::url($restaurant->logo_path) }}" class="h-full w-full object-cover">
                                @else
                                    <div class="h-full w-full flex items-center justify-center text-gray-300">LOGO</div>
                                @endif
                            </div>
                            <input type="file" name="logo" accept="image/*" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cover Photo</label>
                        <div class="h-32 w-full bg-gray-100 rounded-lg overflow-hidden mb-2">
                            @if($restaurant->cover_path)
                                <img src="{{ Storage::url($restaurant->cover_path) }}" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center text-gray-300">COVER PHOTO</div>
                            @endif
                        </div>
                        <input type="file" name="cover" accept="image/*" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-50 flex justify-end">
                <button type="submit" class="bg-orange-600 text-white py-3 px-8 rounded-md text-sm font-bold hover:bg-orange-700 transition shadow-lg shadow-orange-100">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
