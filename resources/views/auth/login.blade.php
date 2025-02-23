<x-app-layout>
    <form method="POST" action="{{ route('login') }}" class="w-[400px] mx-auto p-6 my-16">
        <h2 class="text-2xl font-semibold text-center mb-5">
            Login to your account
        </h2>
        <p class="text-center text-gray-500 mb-6">
            or
            <a
                href="{{ route('register') }}"
                class="text-sm text-pink-400 hover:text-pink-300"
            >
                create new account
            </a>
        </p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')"/>

        @csrf
        <div class="mb-4">
            <x-input type="email" name="email" placeholder="Your email address" :value="old('email')"/>
        </div>
        <div class="mb-4">
            <x-input type="password" name="password" placeholder="Your password" :value="old('password')" />
        </div>
        <div class="flex justify-between items-center mb-5">
            <div class="flex items-center">
                <input
                    id="loginRememberMe"
                    type="checkbox"
                    class="mr-3 rounded border-gray-300 text-pink-500 focus:ring-0 focus:outline-none "
                />
                <label for="loginRememberMe">Remember Me</label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-pink-400 hover:text-pink-300">
                    Forgot Password?
                </a>
            @endif
        </div>
        <button
            class="btn-primary bg-emerald-400 hover:bg-emerald-500 active:bg-emerald-600 w-full"
        >
            Login
        </button>
    </form>
</x-app-layout>
