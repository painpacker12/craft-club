@extends('layouts.app')

@section('content')
<div class="main dp">
    <div class="row">
        <div class="hover"></div>
        <div class="title"></div>
        <div class="row--small grid between">
            <div class="content driver-page">
                <div class="driver-page-photo">
                    <img src="{{ asset('img/' . ($master->photo ?? 'driver-page.png')) }}">
                </div>
                <div class="driver-page-name">{{ $master->name }}</div>
                <div class="driver-page-text">
                    <div class="driver-page-my">Мои мастер-классы</div>
                    <table class="driver-page-table">
                        <tbody>
                            @forelse($masterClasses as $mc)
                            <tr>
                                <td>
                                    {{ \Carbon\Carbon::parse($mc->date)->translatedFormat('j F Y года') }}<br>
                                    @php
                                        $times = explode('-', $mc->time_slot);
                                        $startHour = str_pad($times[0], 2, '0', STR_PAD_LEFT);
                                        $endHour = str_pad($times[1], 2, '0', STR_PAD_LEFT);
                                        $start = $startHour . ':00';
                                        $end = $endHour . ':00';
                                    @endphp
                                    {{ $start }} — {{ $end }}
                                </td>
                                <td>
                                    <b>{{ $mc->title }}</b>
                                    <p>
                                        @php
                                            $registrations = $mc->registrations()->where('status', 'confirmed')->get();
                                        @endphp
                                        @forelse($registrations as $reg)
                                            1. {{ $reg->user->name }} ({{ \Carbon\Carbon::parse($reg->user->birth_date ?? '1990-01-01')->format('d.m.Y') }})<br>
                                            email: {{ $reg->user->email }}<br>
                                            tel: {{ $reg->user->phone }}
                                            @if(!$loop->last)<br><br>@endif
                                        @empty
                                            Нет участников
                                        @endforelse
                                    </p>
                                    <form method="POST" action="{{ route('master.classes.update', $mc->id) }}" style="margin-top: 10px;">
                                        @csrf
                                        @method('PUT')
                                        <textarea name="description" rows="3" style="width: 100%;">{{ $mc->description }}</textarea>
                                        <input type="number" name="price" value="{{ $mc->price }}" style="width: 100px;">
                                        <button type="submit" class="btn" style="margin-top: 5px;">Сохранить</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2">У вас пока нет мастер-классов</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="driver-page-btn-wrapper">
                    <a href="{{ route('master.classes.create') }}" class="driver-page-btn btn">Добавить мастер-класс</a>
                </div>
            </div>
            <ul class="menu">
                @foreach($categories as $category)
                    <li><a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection