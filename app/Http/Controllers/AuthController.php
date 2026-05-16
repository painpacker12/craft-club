<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Форма входа
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Вход
    public function login(Request $request): object
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return back()->withErrors(['email' => 'Неверный email или пароль.']);
        }

        if (!$user->email_verified) {
            return back()->withErrors(['email' => 'Подтвердите email перед входом.']);
        }

        Auth::login($user);  // ← Добавьте это!

        session(['user_id' => $user->id, 'user_name' => $user->name, 'user_role' => $user->role]);

        return redirect()->route('index');
    }
    // Форма регистрации
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Регистрация
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|regex:/^[а-яА-ЯёЁa-zA-Z\s\-]+$/u',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|regex:/^\+?[0-9]{10,15}$/'
        ], [
            'password.min' => 'Пароль должен содержать не менее 6 символов',
            'password.confirmed' => 'Пароли не совпадают',
            'email.required' => 'Email обязателен для заполнения',
            'email.email' => 'Введите корректный email адрес',
            'email.unique' => 'Пользователь с таким email уже существует',
            'name.required' => 'ФИО обязательно для заполнения',
            'name.regex' => 'ФИО может содержать только буквы, пробелы и дефисы',
            'phone.required' => 'Телефон обязателен для заполнения',
            'phone.regex' => 'Телефон должен содержать от 10 до 15 цифр, может начинаться с +',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'role' => 'user'
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Регистрация успешна!');
    }

    // Выход
    public         function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}