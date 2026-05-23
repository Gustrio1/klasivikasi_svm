@extends('layouts.guest')

@section('title', 'Login')

@section('content')

<h2 class="text-xl font-bold text-gray-800 text-center mb-1">Masuk ke Sistem</h2>
<p class="text-sm text-gray-500 text-center mb-6">Gunakan username dan password Anda</p>

{{-- Alert session success --}}
@if(session('success'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        {{ session('success') }}
    </div>
@endif

{{-- Alert session info --}}
@if(session('info'))
    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-700 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        {{ session('info') }}
    </div>
@endif

{{-- Alert General Error --}}
@if($errors->any())
    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 flex items-start gap-2">
        <svg class="w-4 h-4 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div>
            <p class="font-semibold">Terjadi kesalahan:</p>
            <ul class="list-disc list-inside mt-1 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<form method="POST" action="{{ route('login.post') }}" class="space-y-4">
    @csrf

    {{-- Username --}}
    <div>
        <label for="username" class="form-label">Username</label>
        <input
            id="username"
            type="text"
            name="username"
            value="{{ old('username') }}"
            required
            autofocus
            autocomplete="username"
            placeholder="Masukkan username"
            class="form-input @error('username') border-red-400 @enderror"
        >
        @error('username')
            <p class="form-error text-red-500 font-medium italic">{{ $message }}</p>
        @enderror
    </div>

    {{-- Password --}}
    <div>
        <label for="password" class="form-label">Password</label>
        <div class="relative" x-data="{ showPass: false }">
            <input
                id="password"
                :type="showPass ? 'text' : 'password'"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Masukkan password"
                class="form-input pr-10 @error('password') border-red-400 @enderror"
            >
            <button type="button"
                    @click="showPass = !showPass"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <svg x-show="showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
            </button>
        </div>
        @error('password')
            <p class="form-error text-red-500 font-medium italic">{{ $message }}</p>
        @enderror
    </div>

    {{-- Remember Me --}}
    <div class="flex items-center gap-2">
        <input id="remember" type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-teal-600 focus:ring-teal-500">
        <label for="remember" class="text-sm text-gray-600">Ingat saya</label>
    </div>

    {{-- Submit --}}
    <button type="submit"
            class="w-full btn-primary justify-center py-2.5 text-base">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
        </svg>
        Masuk
    </button>
</form>

{{-- Info akun demo --}}
<div class="mt-6 p-3 bg-gray-50 border border-gray-200 rounded-lg">
    <p class="text-xs text-gray-500 font-semibold text-center mb-2">Akun Demo</p>
    <div class="grid grid-cols-3 gap-1 text-xs text-center text-gray-600">
        <div class="bg-blue-50 rounded p-1.5">
            <p class="font-bold text-blue-700">Admin</p>
            <p>admin</p>
        </div>
        <div class="bg-green-50 rounded p-1.5">
            <p class="font-bold text-green-700">Guru</p>
            <p>guru1</p>
        </div>
        <div class="bg-yellow-50 rounded p-1.5">
            <p class="font-bold text-yellow-700">Siswa</p>
            <p>siswa1</p>
        </div>
    </div>
    <p class="text-xs text-gray-400 text-center mt-1.5">Password: <code class="bg-gray-100 px-1 rounded">password</code></p>
</div>

@endsection
