<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\MasterClass;

class FrontController extends Controller
{
    // Главная страница
    public function index()
    {
        $categories = Category::all();
        $myRegistrations = [];
        
        if (auth()->check() && auth()->user()->role === 'user') {
            $myRegistrations = auth()->user()->registrations()
                ->where('status', 'confirmed')
                ->with('masterClass.user')
                ->get()
                ->map(function($reg) {
                    return $reg;
                });
        }
        
        return view('index', compact('categories', 'myRegistrations'));
    }

    // Страница категории (вид творчества)
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $categories = Category::all();
        $masterClasses = MasterClass::where('category_id', $category->id)
            ->with('user', 'category')
            ->orderBy('date')
            ->orderBy('time_slot')
            ->get();
        
        return view('category', compact('category', 'categories', 'masterClasses'));
    }

    // Страница подтверждения записи
    public function confirmBooking($id)
    {
        if (!auth()->check() || auth()->user()->role !== 'user') {
            return redirect()->route('login')->with('error', 'Войдите для записи на мастер-класс');
        }
        
        $masterClass = MasterClass::with('category', 'user')->findOrFail($id);
        
        // Проверка: не прошел ли уже мастер-класс
        if (now()->startOfDay()->gt($masterClass->date)) {
            return redirect()->route('category.show', $masterClass->category->slug)
                ->with('error', 'Этот мастер-класс уже прошел');
        }
        
        // Проверка: есть ли свободные места
        $bookedCount = $masterClass->registrations()->where('status', 'confirmed')->count();
        if ($bookedCount >= $masterClass->max_attendees) {
            return redirect()->route('category.show', $masterClass->category->slug)
                ->with('error', 'Нет свободных мест на этот мастер-класс');
        }
        
        // Проверка: не записан ли уже пользователь
        $alreadyBooked = $masterClass->registrations()
            ->where('user_id', auth()->id())
            ->where('status', 'confirmed')
            ->exists();
        
        if ($alreadyBooked) {
            return redirect()->route('category.show', $masterClass->category->slug)
                ->with('error', 'Вы уже записаны на этот мастер-класс');
        }
        
        return view('booking.confirm', compact('masterClass'));
    }

    // Сохранение записи
    public function storeBooking(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->role !== 'user') {
            return redirect()->route('login')->with('error', 'Войдите для записи на мастер-класс');
        }
        
        $masterClass = MasterClass::findOrFail($id);
        $categorySlug = $masterClass->category->slug;
        
        // Проверки
        if (now()->startOfDay()->gt($masterClass->date)) {
            return redirect()->route('category.show', $categorySlug)
                ->with('error', 'Этот мастер-класс уже прошел');
        }
        
        $bookedCount = $masterClass->registrations()->where('status', 'confirmed')->count();
        if ($bookedCount >= $masterClass->max_attendees) {
            return redirect()->route('category.show', $categorySlug)
                ->with('error', 'Нет свободных мест на этот мастер-класс');
        }
        
        $alreadyBooked = $masterClass->registrations()
            ->where('user_id', auth()->id())
            ->where('status', 'confirmed')
            ->exists();
        
        if ($alreadyBooked) {
            return redirect()->route('category.show', $categorySlug)
                ->with('error', 'Вы уже записаны на этот мастер-класс');
        }
        
        // Создаем запись
        \App\Models\Registration::create([
            'user_id' => auth()->id(),
            'master_class_id' => $masterClass->id,
            'status' => 'confirmed'
        ]);
        
        return redirect()->route('category.show', $categorySlug)
            ->with('success', 'Вы успешно записаны на мастер-класс "' . $masterClass->title . '"');
    }
}