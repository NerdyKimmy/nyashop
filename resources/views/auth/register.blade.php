<x-app-layout>
    <form
        action="{{ route('register') }}"
        method="post"
        class="w-[400px] mx-auto p-6 my-16"
    >
        @csrf

        <h2 class="text-2xl font-semibold text-center mb-4">Create an account</h2>
        <p class="text-center text-gray-500 mb-3">
            or
            <a
                href="{{ route('login') }}"
                class="text-sm text-pink-400 hover:text-pink-500"
            >
                login with existing account
            </a>
        </p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')"/>

        <div class="mb-4">
            <x-input placeholder="Your nickname" type="text" name="name" :value="old('name')" />
        </div>
        <div class="mb-4">
            <x-input placeholder="Your Email" type="email" name="email" :value="old('email')" />
        </div>
        <div class="mb-4">
            <x-input placeholder="Password" type="password" name="password"/>
        </div>
        <div class="mb-4">
            <x-input placeholder="Repeat Password" type="password" name="password_confirmation"/>
        </div>

        <button
            class="btn-primary bg-emerald-400 hover:bg-emerald-500 active:bg-emerald-600 w-full"
        >
            Signup
        </button>
    </form>
</x-app-layout>
