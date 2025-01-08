@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Your Tests</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-6">
        @foreach($tests as $test)
            <div class="border border-gray-300 rounded-lg shadow-md overflow-hidden">
                <!-- Test Header -->
                <div class="bg-gray-100 px-6 py-4 flex justify-between items-center cursor-pointer" onclick="toggleQuestions('test-{{ $test->id }}')">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">{{ $test->title }}</h3>
                        <p class="text-sm text-gray-600">{{ $test->description }}</p>
                        <p class="text-sm text-gray-600"><strong>Time Limit:</strong> {{ $test->time_limit }} minutes</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" id="icon-test-{{ $test->id }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                <!-- Questions and Results Section -->
                <div class="bg-white px-6 py-4 hidden" id="test-{{ $test->id }}">
                    @php
                        $hasSubmission = $test->submissions->where('user_id', auth()->id())->isNotEmpty();
                    @endphp

                    @if(!$hasSubmission)
                        <!-- Before attempting the test -->
                        <p class="text-gray-600">You have not attempted this test yet.</p>
                        <a href="{{ route('tests.start', $test->id) }}" class="px-4 py-2 bg-blue-500 text-black rounded-md hover:bg-blue-600">Start Test</a>
                    @else
                        <!-- After attempting the test -->
                        <h4 class="text-lg font-bold text-gray-700 mb-3">Your Results</h4>
                        <table class="table-auto w-full text-left border-collapse mb-4">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="px-4 py-2">Attempt Date</th>
                                    <th class="px-4 py-2">Score</th>
                                    <th class="px-4 py-2">Max Score</th>
                                    <th class="px-4 py-2">Time Taken</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($test->submissions->where('user_id', auth()->id()) as $submission)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $submission->submitted_at->format('d M Y, H:i') }}</td>
                                        <td class="border px-4 py-2">{{ $submission->score }}</td>
                                        <td class="border px-4 py-2">{{ $test->questions->sum('points') }}</td>
                                        <td class="border px-4 py-2">{{ gmdate('H:i:s', $submission->time_taken ?? $submission->submitted_at->diffInSeconds($submission->started_at ?? now())) }} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <h4 class="text-lg font-bold text-gray-700 mb-3">Questions and Answers</h4>
                        @foreach($test->questions as $question)
                            <div class="border-l-4 border-blue-500 pl-4 mb-4">
                                <p class="font-semibold text-gray-800 text-lg mr-6 mb-2">
                                    {{ $loop->iteration }}. {{ $question->question_text }} ({{ $question->points }} points)
                                </p>
                                <ul class="list-disc pl-6 space-y-2">
                                    @foreach($question->answers as $key => $answer)
                                        <li class="flex items-center">
                                            <span class="font-medium text-gray-700 mr-2">{{ chr(97 + $key) }})</span>
                                            <span class="text-gray-700">{{ $answer->answer_text }}</span>
                                            @if($answer->is_correct)
                                                <span class="ml-2 text-green-600 font-semibold">(Correct)</span>
                                            @endif

                                            @php
                                                $userAnswer = $test->submissions
                                                    ->where('user_id', auth()->id())
                                                    ->first()
                                                    ->submissionAnswers
                                                    ->where('question_id', $question->id)
                                                    ->where('answer_id', $answer->id)
                                                    ->isNotEmpty();
                                            @endphp

                                            @if($userAnswer)
                                                <span class="ml-2 text-blue-600 font-semibold">(Your Answer)</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    function toggleQuestions(testId) {
        const section = document.getElementById(testId);
        const icon = document.getElementById('icon-' + testId);

        if (section.classList.contains('hidden')) {
            section.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            section.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    }
</script>
@endsection