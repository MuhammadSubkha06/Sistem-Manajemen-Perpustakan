<x-guest-layout>
    <div class="mb-5">
        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Kembali</a>
        <h2 class="mt-4 text-2xl font-bold text-gray-900">Login Anggota</h2>
        <p class="mt-1 text-sm text-gray-600">Gunakan NIS dan password yang diberikan admin.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('user.login.store') }}">
        @csrf

        <div>
            <x-input-label for="nis" value="NIS" />
            <x-text-input id="nis" class="block mt-1 w-full" type="text" name="nis" :value="old('nis')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('nis')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>Masuk Anggota</x-primary-button>
        </div>
    </form>
</x-guest-layout>
