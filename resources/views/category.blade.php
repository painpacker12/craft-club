@extends('layouts.app')

@section('content')
<div class="main">
    <div class="row">
        <div class="hover"></div>
        <div class="title">Категории</div>
        <div class="row--small grid between">
            <div class="content">
                <img src="{{ asset('img/' . ($category->image ?? 'elifant.png')) }}" alt="{{ $category->name }}">
                <p>{{ $category->description }}</p>
            </div>
            <ul class="menu">
                @foreach($categories as $cat)
                    <li><a href="{{ route('category.show', $cat->slug) }}">{{ $cat->name }}</a></li>
                @endforeach
            </ul>
        </div>

        <div class="row shedule">
            <div class="row--small">
                <h2>Расписание</h2>
                <div class="drivers">
                    @forelse($masterClasses as $mc)
                        @php
                            $bookedCount = $mc->registrations()->where('status', 'confirmed')->count();
                            $isFull = $bookedCount >= $mc->max_attendees;
                            $isPast = \Carbon\Carbon::parse($mc->date)->isPast();
                            $canBook = auth()->check() && auth()->user()->role === 'user' && !$isPast && !$isFull;
                            $alreadyBooked = auth()->check() && auth()->user()->role === 'user' && 
                                            $mc->registrations()->where('user_id', auth()->id())->where('status', 'confirmed')->exists();
                        @endphp
                        <div class="driver grid">
                            <div class="driver-left grid">
                                <div class="driver-photo">
                                    <img src="{{ asset('img/' . ($mc->user->photo ?? 'driver1.png')) }}">
                                </div>
                                <div class="driver-text">
                                    <div class="driver-name">{{ $mc->title }}</div>
                                    <div class="driver-desc">{{ $mc->description }}</div>
                                </div>
                            </div>
                            <div class="driver-right">
                                <div class="driver-time">
                                    {{ \Carbon\Carbon::parse($mc->date)->translatedFormat('j F Y года') }}<br>
                                    @php
                                        $times = explode('-', $mc->time_slot);
                                        $startHour = str_pad($times[0], 2, '0', STR_PAD_LEFT);
                                        $endHour = str_pad($times[1], 2, '0', STR_PAD_LEFT);
                                        $start = $startHour . ':00';
                                        $end = $endHour . ':00';
                                    @endphp
                                    {{ $start }} — {{ $end }}
                                </div>
                                <div class="driver-time">Стоимость: {{ $mc->price }} руб.</div>
                                <div class="driver-time">Свободно мест: {{ $mc->max_attendees - $bookedCount }} из {{ $mc->max_attendees }}</div>
                                @if($isPast)
                                    <button class="driver-btn btn-disabled" disabled>Прошедший</button>
                                @elseif($isFull)
                                    <button class="driver-btn btn-disabled" disabled>Нет мест</button>
                                @elseif($alreadyBooked)
                                    <button class="driver-btn btn-disabled" disabled>Вы уже записаны</button>
                                @elseif($canBook)
                                    <a href="{{ route('booking.confirm', $mc->id) }}" class="driver-btn">Записаться</a>
                                @else
                                    <button class="driver-btn btn-disabled" disabled>Войдите для записи</button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p>Нет доступных мастер-классов в этой категории.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection