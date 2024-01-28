@extends('layouts.app')

@section('content')
    <h1>ახალი ქვიზი</h1>

    <form action="{{ route('quizzes.store') }}" method="post">
        @csrf

        <label for="title">სათაური:</label>
        <input type="text" name="title" required>

        <label for="question_count">რაოდენობა:</label>
        <input type="number" name="question_count" required>

        <button type="submit">შექმნა</button>
    </form>
@endsection