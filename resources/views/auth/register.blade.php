@extends('layouts.app')

@section('content')
<div class="main">
    <div class="row">
        <div class="row--small">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <h2>Форма регистрации</h2>
                
                <div class="form-group">
                    <label>ФИО</label>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                    @error('name') <div style="color: red;">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                    @error('email') <div style="color: red;">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password" required>
                    @error('password') <div style="color: red;">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group">
                    <label>Подтверждение пароля</label>
                    <input type="password" name="password_confirmation" required>
                </div>
                
                <div class="form-group">
                    <label>Номер телефона</label>
                    <input type="tel" name="phone" pattern="^\+?[0-9]{10,15}$" value="{{ old('phone') }}" required>
                    @error('phone') <div style="color: red;">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group">
                    <button class="btn">Зарегистрироваться</button>
                </div>
                
                <p>Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a></p>
            </form>
        </div>
    </div>
</div>
@endsection