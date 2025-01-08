<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Submission;
use App\Models\SubmissionAnswer;
use App\Models\Answer;

class TestController extends Controller
{
    public function create()
    {
        Log::info('Authenticated User:', ['user' => Auth::user()]);
        return view('tests.create');
    }

    public function index()
    {
        $tests = Test::with(['questions.answers', 'submissions'])->where('created_by', Auth::id())->get();
    
        return view('tests.index', compact('tests'));
    }

    public function start(Test $test)
{
    // Записване на началното време на теста
    $submission = Submission::firstOrCreate(
        [
            'test_id' => $test->id,
            'user_id' => Auth::id(),
        ],
        [
            'started_at' => now(), // Задаване на началното време
        ]
    );

    return view('tests.start', compact('test', 'submission'));
}

public function submit(Request $request, Test $test)
{
    $submission = Submission::where('test_id', $test->id)
        ->where('user_id', Auth::user()->id)
        ->latest()
        ->first();

        if (!$submission) {
            return redirect()->route('tests.index')->withErrors('Submission not found.');
        }

    $submission->update(['submitted_at' => now()]);

    $score = 0;

    foreach ($test->questions as $question) {
        $selectedAnswerId = $request->input('questions.' . $question->id);
        $isCorrect = false;

        if ($selectedAnswerId) {
            $answer = Answer::find($selectedAnswerId);
            $isCorrect = $answer && $answer->is_correct;

            SubmissionAnswer::create([
                'submission_id' => $submission->id,
                'question_id' => $question->id,
                'answer_id' => $selectedAnswerId,
                'is_correct' => $isCorrect,
            ]);

            if ($isCorrect) {
                $score += $question->points;
            }
        }
    }

    $timeTaken = $submission->started_at ? now()->diffInSeconds($submission->started_at) : null;

    $submission->update([
        'score' => $score,
        'time_taken' => $timeTaken,
    ]);

    return redirect()->route('results.index')->with('success', 'Test completed successfully!');
}

public function show(Test $test)
{
    $test->load(['questions.answers']); 

    return view('tests.show', compact('test'));
}

public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'time_limit' => 'required|integer|min:1',
        'questions' => 'required|array',
        'questions.*.text' => 'required|string|max:1000',
        'questions.*.answers' => 'required|array|min:2', 
        'questions.*.points' => 'required|integer|min:1',
        'questions.*.answers.*.text' => 'required|string|max:500',
        'questions.*.answers.*.is_correct' => 'nullable|boolean',
    ]);


    $test = Test::create([
        'title' => $request->title,
        'description' => $request->description,
        'time_limit' => $request->time_limit,
        'created_by' => Auth::user()->id,
    ]);

     foreach ($request->questions as $questionData) {
        $question = $test->questions()->create([
            'question_text' => $questionData['text'],
            'points' => $questionData['points'], 
        ]);

        foreach ($questionData['answers'] as $answerData) {
            $question->answers()->create([
                'answer_text' => $answerData['text'],
                'is_correct' => isset($answerData['is_correct']) && $answerData['is_correct'],
            ]);
        }
    }

    return redirect()->route('tests.index')->with('success', 'Test created successfully.');
}
}
