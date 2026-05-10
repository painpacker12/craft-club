@extends('layouts.app')

@section('content')
<div class="main">
    <div class="row">
        <div class="row--small">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <h2>Вход</h2>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                    @error('email') <div style="color: red;">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <button class="btn">Войти</button>
                </div>
                
                <p>Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a></p>
            </form>
        </div>
    </div>
</div>
@endsection