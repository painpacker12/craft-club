@extends('layouts.app')

@section('content')
<div class="main">
    <div class="row">
        <div class="row--small">
            <form method="POST" action="{{ route('booking.store', $masterClass->id) }}">
                @csrf
                <h2>Подтверждение записи</h2>
                
                <p><strong>ФИО пользователя:</strong> {{ auth()->user()->name }}</p>
                <p><strong>Вид творчества:</strong> {{ $masterClass->category->name }}</p>
                <p><strong>ФИО мастера:</strong> {{ $masterClass->user->name }}</p>
                @php
                    $times = explode('-', $masterClass->time_slot);
                    $start = str_pad($times[0], 2, '0', STR_PAD_LEFT) . ':00';
                    $end = str_pad($times[1], 2, '0', STR_PAD_LEFT) . ':00';
                @endphp
                <p><strong>Дата:</strong> {{ \Carbon\Carbon::parse($masterClass->date)->translatedFormat('j F Y года') }}</p>
                <p><strong>Время:</strong> {{ $start }} — {{ $end }}</p>
                <p><strong>Стоимость:</strong> {{ $masterClass->price }} руб.</p>
                
                <div class="form-group">
                    <button type="submit" class="btn">Подтвердить</button>
                    <a href="{{ route('category.show', $masterClass->category->slug) }}" class="btn">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection