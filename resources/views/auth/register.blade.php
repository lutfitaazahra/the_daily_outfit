@extends('layouts.app')

@section('title', 'Daftar — The Daily Outfit')

@section('content')
<div class="auth-page">
    <div class="auth-container">
        <div class="auth-brand">
            <div class="auth-brand-inner">
                <a href="{{ route('home') }}" class="brand-logo">The Daily Outfit</a>
                <p class="auth-tagline">Your everyday style, elevated. Bergabung dan temukan gaya terbaikmu.</p>
            </div>
        </div>
        <div class="auth-card">
            <div class="auth-card-inner">
                <h2>Buat Akun</h2>
                <p class="auth-sub">Bergabung dengan The Daily Outfit</p>

                @if($errors->any())
                    <div class="alert alert-error">{{ $errors->first() }}</div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="auth-form">
                    @csrf
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" placeholder="Nama kamu" value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="email@kamu.com" value="{{ old('email') }}" required>
                    </div>
                    <div class="form-group">
                        <label>No. HP</label>
                        <input type="text" name="phone" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Min. 6 karakter" required>
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full">Daftar</button>
                </form>
                <p class="auth-link">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
            </div>
        </div>
    </div>
</div>
@endsection