<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\MasterClass;
use Illuminate\Support\Facades\Auth;

class MasterController extends Controller
{
    // Личный кабинет ведущего
    public function dashboard()
    {
        $master = Auth::user();
        $categories = Category::all();
        $masterClasses = MasterClass::where('user_id', $master->id)
            ->with('registrations.user')
            ->orderBy('date', 'desc')
            ->get();
        
        return view('master.dashboard', compact('master', 'categories', 'masterClasses'));
    }

    // Форма добавления мастер-класса
    public function createClass()
    {
        $categories = Category::all();
        return view('master.create', compact('categories'));
    }

    // Сохранение мастер-класса
    public function storeClass(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|in:9-11,11-13,13-15,15-17',
            'max_attendees' => 'required|integer|min:1|max:50',
            'price' => 'required|numeric|min:0'
        ]);

        // Проверка на конфликт времени
        $existing = MasterClass::where('user_id', Auth::id())
            ->where('date', $validated['date'])
            ->where('time_slot', $validated['time_slot'])
            ->exists();
        
        if ($existing) {
            return back()->withInput()->with('error', 'У вас уже запланирован мастер-класс на это время');
        }

        MasterClass::create([
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'date' => $validated['date'],
            'time_slot' => $validated['time_slot'],
            'max_attendees' => $validated['max_attendees'],
            'price' => $validated['price']
        ]);

        return redirect()->route('master.dashboard')->with('success', 'Мастер-класс добавлен');
    }

    // Редактирование мастер-класса
    public function updateClass(Request $request, $id)
    {
        $masterClass = MasterClass::where('user_id', Auth::id())->findOrFail($id);
        
        $validated = $request->validate([
            'description' => 'required|string',
            'price' => 'required|numeric|min:0'
        ]);
        
        $masterClass->update([
            'description' => $validated['description'],
            'price' => $validated['price']
        ]);
        
        return back()->with('success', 'Мастер-класс обновлен');
    }
}