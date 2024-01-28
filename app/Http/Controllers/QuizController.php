<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class QuizController extends Controller
{
    public function start(Quiz $quiz)
    {
        auth()->user()->quizzes()->attach($quiz, ['answered_questions' => 0]);

        return redirect()->route('quizzes.show', $quiz);
    }


    public function index()
    {
        $quizzes = Quiz::all();
        return view('quizzes.index', compact('quizzes'));
        
        $quizzes = Quiz::orderBy('created_at', 'desc')->get();
        return view('quizzes.index', compact('quizzes'));
    }

    public function answer(Request $request, Quiz $quiz)
    {
        $user = auth()->user();
        $answeredQuestions = $user->quizzes()->where('quiz_id', $quiz->id)->first()->pivot->answered_questions;

        $request->validate([
            'answers.*' => 'required|exists:answers,id',
        ]);

        foreach ($request->input('answers') as $questionId => $selectedAnswerId) {
            $question = $quiz->questions()->findOrFail($questionId);

            $isCorrect = $question->correctAnswer->id == $selectedAnswerId;

            $user->answers()->create([
                'quiz_id' => $quiz->id,
                'question_id' => $questionId,
                'selected_answer_id' => $selectedAnswerId,
                'is_correct' => $isCorrect,
            ]);
        }

        $user->quizzes()->updateExistingPivot($quiz, ['answered_questions' => ++$answeredQuestions]);

        return redirect()->route('quizzes.show', $quiz);
    }

    public function checkAnswer(Request $request, Quiz $quiz)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'selected_answer_id' => [
                'required',
                Rule::in($quiz->questions()->find($request->input('question_id'))->answers->pluck('id')->toArray())
            ],
        ]);

        $question = $quiz->questions()->find($request->input('question_id'));
        $isCorrect = $question->correctAnswer->id == $request->input('selected_answer_id');

        return response()->json(['correct' => $isCorrect]);
    }

    public function create()
    {
        return view('quizzes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'question_count' => 'required|integer|min:1',
        ]);

        $quiz = Quiz::create([
            'title' => $request->input('title'),
            'user_id' => auth()->id(),
        ]);

        for ($i = 0; $i < $request->input('question_count'); $i++) {
            $question = Question::create([
                'question_text' => 'kitxva',
                'photo_path' => 'path/to/your/photo.jpg',
            ]);

            $answers = [
                ['answer_text' => 'Answer 1', 'is_correct' => true, 'position' => 'A'],
                ['answer_text' => 'Answer 2', 'is_correct' => false, 'position' => 'B'],
                ['answer_text' => 'Answer 3', 'is_correct' => false, 'position' => 'C'],
                ['answer_text' => 'Answer 4', 'is_correct' => false, 'position' => 'D'],
            ];

            foreach ($answers as $answerData) {
                $question->answers()->create($answerData);
            }
        }

        return redirect()->route('quizzes.show', $quiz)->with('success', 'ქვიზი შექმნილია');
    }

    public function edit(Quiz $quiz)
    {
        return view('quizzes.edit', compact('quiz'));
    }

    public function show(Quiz $quiz)
    {
        $user = auth()->user();
        $quiz->load(['user' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }, 'questions.answers']);

        return view('quizzes.show', compact('quiz'));
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('dashboard')->with('success', 'Quiz deleted successfully');
    }
}