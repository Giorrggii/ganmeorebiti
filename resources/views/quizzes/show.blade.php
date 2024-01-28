@extends('layouts.app')

@section('content')
    <h1>{{ $quiz->title }}</h1>
    <p>სულ კითხვა: {{ $quiz->questions->count() }}</p>
    <p>ავტორი: {{ $quiz->user->name }}</p>
    @if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    @endif
    @if ($quiz->main_photo)
        <img src="{{ asset('storage/' . $quiz->main_photo) }}" alt="{{ $quiz->title }}" style="max-width: 300px;">
    @endif
    <p>{{ $quiz->rame }}</p>

    @if (auth()->check())
        <form action="{{ route('start.quiz', $quiz) }}" method="post">
            @csrf
            <button type="submit">დაიწყოს ქვიზი</button>
        

    <h2>კითხვები</h2>
    <p>კითხვების პროგრესი: {{ $quiz->user->pivot->answered_questions }} / {{ $quiz->questions->count() }}</p>
    <ul>
        @foreach ($quiz->questions as $question)
            <li>
                <strong>{{ $question->question_text }}</strong><br>
                    @foreach ($question->answers as $answer)
                        <label>
                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $answer->id }}">
                            {{ $answer->answer_text }}
                        </label><br>
                    @endforeach
                <ul>
                    @foreach ($question->answers as $answer)
                        <li>{{ $answer->answer_text }} - {{ $answer->is_correct ? 'სწორი' : 'არასწორი' }}</li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
    <button type="submit">დასრულება</button>
    </form>

    <div id="quizResult" style="display: none;">
        <h2>კვიზის შედეგი</h2>
        <p>სწორი პასუხები: <span id="correctAnswersCount">0</span></p>
        <p>კითხვების რაოდენობა: {{ $quiz->questions->count() }}</p>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#submitQuiz').on('click', function () {
                $('#quizForm').submit();
            });

            $('input[type="radio"]').on('change', function () {
                const questionId = $(this).data('question-id');
                const selectedAnswerId = $(this).data('answer-id');

                $.post('{{ route('check-answer.quiz', $quiz) }}', {
                    _token: '{{ csrf_token() }}',
                    question_id: questionId,
                    selected_answer_id: selectedAnswerId
                }, function (data) {
                    const resultElement = $('#result_' + questionId);
                    resultElement.text(data.correct ? ' - სწორი' : ' - არასწორი');
                    resultElement.css('color', data.correct ? 'green' : 'red');
                });
            });

            $('#quizForm').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function () {
                        $('#quizForm').hide();
                        $('#quizResult').show();

                        $.get('{{ route('quizzes.show', $quiz) }}', function (data) {
                            $('#correctAnswersCount').text(data.correctAnswersCount);
                        });
                    }
                });
            });
        });
    </script>
@endif
@endsection