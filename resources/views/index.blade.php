@extends('layouts.app')

@section('content')
<div class="main">
    <div class="row">
        <div class="hover"></div>
        <div class="title">Главная</div>
        <div class="row--small grid between">
            <div class="content">
                <img src="{{ asset('img/elifant.png') }}" alt="Слон">
                <p>Архитектурное моделирование — это изготовление моделей зданий, сооружений, исторических памятников, а также инженерных и фортификационных сооружений. <span>Отличительной особенностью</span> образовательной программы является то, что она расширяет пространство.</p>
                <p>Данная программа не имеет аналогов среди образовательных дополнительных программ, так как впервые для изготовления макетов применяются бамбуковые палочки, в качестве основного элемента к онструкции, что позволяет значительно упростить технологию создания макета и обучить начальным навыкам деревообработки.</p>
                <p><span>Актуальность</span> предлагаемой программы состоит в том, что мастер-классы по архитектурному моделированию способствуют воспитанию удожественно-эмоционального отношения к работе и творчеству, готовым изделиям; умению наблюдать и создавать образы, композиции, архитектурные ансамбли, ландшафтные построения; овладению навыками дизайна; воспитанию бережного отношения к культурному наследию своего города, России; воспитанию гордости за свой народ, поддержание  интереса к его истории и  культуре.</p>
            </div>
            <ul class="menu">
                @foreach($categories as $category)
                    <li><a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a></li>
                @endforeach
            </ul>
        </div>

        @if(auth()->check() && auth()->user()->role === 'user')
            <div class="row shedule">
                <div class="row--small">
                    <h2>Мои записи</h2>
                    <div class="drivers">
                        @forelse($myRegistrations as $reg)
                            <div class="driver grid">
                                <div class="driver-left grid">
                                    <div class="driver-photo">
                                        <img src="{{ asset('img/' . ($reg->masterClass->user->photo ?? 'driver1.png')) }}">
                                    </div>
                                    <div class="driver-text">
                                        <div class="driver-name">{{ $reg->masterClass->title }}</div>
                                        <div class="driver-desc">{{ $reg->masterClass->description }}</div>
                                    </div>
                                </div>
                                <div class="driver-right">
                                    <div class="driver-time">
                                        {{ \Carbon\Carbon::parse($reg->masterClass->date)->translatedFormat('j F Y года') }},
                                        @php
                                            $times = explode('-', $reg->masterClass->time_slot);
                                            $startHour = str_pad($times[0], 2, '0', STR_PAD_LEFT);
                                            $endHour = str_pad($times[1], 2, '0', STR_PAD_LEFT);
                                            $start = $startHour . ':00';
                                            $end = $endHour . ':00';
                                        @endphp
                                        {{ $start }} — {{ $end }}
                                    </div>
                                    <div class="driver-time">Ведущий: {{ $reg->masterClass->user->name }}</div>
                                </div>
                            </div>
                        @empty
                            <p>Вы еще не записаны ни на один мастер-класс.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection 