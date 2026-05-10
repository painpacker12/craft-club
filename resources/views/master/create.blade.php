@extends('layouts.app')

@section('content')
<div class="main">
    <div class="row">
        <div class="row--small">
            <form method="POST" action="{{ route('master.classes.store') }}">
                @csrf
                <h2>Форма добавления мастер-класса</h2>
                
                @if($errors->any())
                    <div style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 4px;">
                        @foreach($errors->all() as $error)
                            <p style="margin: 5px 0;">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="form-group">
                    <label>Вид творчества</label>
                    <select name="category_id" required>
                        <option value="">Выберите вид творчества</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Название мастер-класса</label>
                    <input type="text" name="title" value="{{ old('title') }}" required>
                </div>

                <div class="form-group">
                    <label>Описание мастер-класса</label>
                    <textarea name="description" rows="8" required>{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Дата</label>
                    <input type="date" name="date" value="{{ old('date') }}" min="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label>Время</label>
                    <select name="time_slot" required>
                        <option value="">Выберите время</option>
                        <option value="9-11" {{ old('time_slot') == '9-11' ? 'selected' : '' }}>9:00 - 11:00</option>
                        <option value="11-13" {{ old('time_slot') == '11-13' ? 'selected' : '' }}>11:00 - 13:00</option>
                        <option value="13-15" {{ old('time_slot') == '13-15' ? 'selected' : '' }}>13:00 - 15:00</option>
                        <option value="15-17" {{ old('time_slot') == '15-17' ? 'selected' : '' }}>15:00 - 17:00</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Количество человек в группе</label>
                    <input type="number" name="max_attendees" value="{{ old('max_attendees') }}" min="1" max="50" required>
                </div>

                <div class="form-group">
                    <label>Стоимость (руб.)</label>
                    <input type="number" name="price" value="{{ old('price') }}" min="0" step="100" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn">Отправить</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection