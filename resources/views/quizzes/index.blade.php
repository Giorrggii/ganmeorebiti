@extends('layouts.app')

@section('content')
    <h1>ქვიზების სია</h1>

    <ul>
        @foreach ($quizzes as $quiz)
            <li>
                <h2><a href="{{ route('quizzes.show', $quiz) }}">{{ $quiz->title }}</a></h2>
                @if ($quiz->main_photo)
                    <img src="{{ asset('storage/' . $quiz->main_photo) }}" alt="{{ $quiz->title }}" style="max-width: 300px;">
                @endif
                <p>კითხვების რაოდენობა: {{ $quiz->questions->count() }}</p>
                <p>შექმნილია {{ $quiz->created_at->format('Y-m-d H:i:s') }}</p>
            </li>
        @endforeach
    </ul>
@endsection
