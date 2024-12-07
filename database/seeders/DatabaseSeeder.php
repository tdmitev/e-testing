<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Submission;
use App\Models\SubmissionAnswer;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        
        $teacher = User::factory()->create([
            'name' => 'Teacher User',
            'email' => 'teacher@example.com',
            'password' => bcrypt('password'),
            'role' => 'teacher',
        ]);

       
        $students = User::factory(5)->create([
            'role' => 'student',
        ]);

       
        $tests = Test::factory(3)
            ->has(
                Question::factory(5)
                    ->has(
                        Answer::factory(4) 
                    )
            )
            ->create([
                'created_by' => $teacher->id, 
            ]);

        
        foreach ($students as $student) {
            foreach ($tests as $test) {
                $submission = Submission::create([
                    'test_id' => $test->id,
                    'user_id' => $student->id,
                    'score' => null, 
                    'submitted_at' => now(),
                ]);

                foreach ($test->questions as $question) {
                    $correctAnswer = $question->answers->where('is_correct', true)->first();
                    $chosenAnswer = $correctAnswer ?: $question->answers->random();

                    SubmissionAnswer::create([
                        'submission_id' => $submission->id,
                        'question_id' => $question->id,
                        'answer_id' => $chosenAnswer->id,
                        'is_correct' => $chosenAnswer->is_correct,
                    ]);
                }

                $totalScore = $submission->submissionAnswers->where('is_correct', true)->sum('question.points');
                $submission->update(['score' => $totalScore]);
            }
        }
    }
}