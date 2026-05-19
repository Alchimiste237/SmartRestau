@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-8 sm:p-12">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Setup your restaurant</h1>
            <p class="text-gray-600">Provide some details to create your digital storefront.</p>
        </div>

        <form action="{{ route('owner.onboarding.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <!-- Restaurant Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Restaurant Name</label>
                    <input type="text" name="name" id="name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-3 px-4 focus:ring-orange-500 focus:border-orange-500 sm:text-sm" placeholder="e.g. Urban Grill">
                </div>

                <!-- Business Type -->
                <div>
                    <label for="business_type" class="block text-sm font-medium text-gray-700">Business Type</label>
                    <select id="business_type" name="business_type" required class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-3 px-4 focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                        <option value="">Select a type</option>
                        <option value="Fast-food">Fast-food</option>
                        <option value="Casual Dining">Casual Dining</option>
                        <option value="Premium/Fine Dining">Premium/Fine Dining</option>
                        <option value="Bakery/Cafe">Bakery/Cafe</option>
                        <option value="Hotel Restaurant">Hotel Restaurant</option>
                    </select>
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700">Location (City, Neighborhood)</label>
                    <input type="text" name="location" id="location" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-3 px-4 focus:ring-orange-500 focus:border-orange-500 sm:text-sm" placeholder="e.g. Bastos, Yaoundé">
                </div>

                <!-- Contact -->
                <div>
                    <label for="contact" class="block text-sm font-medium text-gray-700">Contact Number (Public)</label>
                    <input type="text" name="contact" id="contact" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-3 px-4 focus:ring-orange-500 focus:border-orange-500 sm:text-sm" placeholder="e.g. +237 6...">
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-md shadow-sm text-lg font-bold text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Create My Dashboard
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
