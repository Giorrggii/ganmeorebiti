<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Quiz;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {$quiz = new Quiz();
        $quiz->name = 'ქვიზი 1';
        $quiz->description = 'პირველი ქვიზი';
        $quiz->num_questions = 5;
        $quiz->save();}
}

$quiz->questions()->createMany([
    ['question' => 'რა არის 1+1?', 'answer' => '2'],
    ['question' => 'რა არის 2x3?', 'answer' => '6'],
    ['question' => 'რა არის 10-5?', 'answer' => '5'],
    ['question' => 'რა არის 3^2?', 'answer' => '9'],
    ['question' => 'რა არის √25?', 'answer' => '5'],
]);