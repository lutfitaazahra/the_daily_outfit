@extends('layouts.app')

@section('title', 'Masuk - The Daily Outfit')

@section('content')
<div class="auth-page">
    <div class="auth-container">
        <div class="auth-brand">
            <div class="auth-brand-inner">
                <a href="{{ route('home') }}" class="brand-logo">The Daily Outfit</a>
                <p class="auth-tagline">Your Daily Fit Starts Here 🔥 Temukan outfit yang cocok dengan vibe dan aktivitasmu hari ini.</p>
            </div>
        </div>
        <div class="auth-card">
            <div class="auth-card-inner">
                <h2>Selamat Datang</h2>
                <p class="auth-sub">Masuk ke akun The Daily Outfit kamu</p>

                @if($errors->any())
                    <div class="alert alert-error">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="auth-form">
                    @csrf
                    <div class="form-group icon-email">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="email@kamu.com" value="{{ old('email') }}" required>
                    </div>
                    <div class="form-group icon-password">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full">Masuk</button>
                </form>
                <p class="auth-link">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
            </div>
        </div>
    </div>
</div>
@endsection