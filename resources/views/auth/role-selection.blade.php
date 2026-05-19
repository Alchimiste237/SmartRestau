@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-sm border border-gray-100">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Complete your profile
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Tell us who you are
            </p>
        </div>
        <form class="mt-8 space-y-6" action="{{ route('auth.set-role') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input id="name" name="name" type="text" required class="mt-1 block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm" placeholder="Your name">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">I am a...</label>
                    <div class="mt-2 grid grid-cols-2 gap-4">
                        <label class="border rounded-md p-4 flex flex-col items-center cursor-pointer hover:bg-gray-50 border-gray-200">
                            <input type="radio" name="role" value="owner" class="sr-only" required>
                            <span class="text-lg font-bold">Restaurant Owner</span>
                            <span class="text-xs text-gray-500 text-center">I want to manage my restaurant</span>
                        </label>
                        <label class="border rounded-md p-4 flex flex-col items-center cursor-pointer hover:bg-gray-50 border-gray-200">
                            <input type="radio" name="role" value="customer" class="sr-only" required>
                            <span class="text-lg font-bold">Customer</span>
                            <span class="text-xs text-gray-500 text-center">I want to view menus and order</span>
                        </label>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="text-red-500 text-sm">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    Continue
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Simple JS to highlight selected role
    document.querySelectorAll('input[name="role"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('input[name="role"]').forEach(r => {
                r.parentElement.classList.remove('border-orange-500', 'bg-orange-50');
                r.parentElement.classList.add('border-gray-200');
            });
            if (this.checked) {
                this.parentElement.classList.remove('border-gray-200');
                this.parentElement.classList.add('border-orange-500', 'bg-orange-50');
            }
        });
    });
</script>
@endsection
