<x-app-layout>

    <form action="{{ route('password.email') }}" method="post" class="w-[400px] mx-auto p-6 my-16">
        @csrf
        <h2 class="text-2xl font-semibold text-center mb-5">
            Enter your Email to reset password
        </h2>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')"/>

        <p class="text-center text-gray-500 mb-6">
            or
            <a
                href="{{ route('login') }}"
                class="text-pink-400 hover:text-pink-300"
            >
                login with existing account
            </a>
        </p>

        <div class="mb-3">
            <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                     autofocus placeholder="Enter your Email Address"/>
        </div>
        <button
            class="btn-primary bg-emerald-400 hover:bg-emerald-500 active:bg-emerald-600 w-full"
        >
            Email Password Reset Link
        </button>
    </form>
</x-app-layout>
