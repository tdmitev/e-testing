@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold text-gray-800">{{ $test->title }}</h1>
    <p class="text-gray-600">{{ $test->description }}</p>
    <p class="text-gray-600"><strong>Time Limit:</strong> {{ $test->time_limit }} minutes</p>

    <div class="mt-6">
        <h2 class="text-xl font-semibold text-gray-700">Questions</h2>
        @foreach($test->questions as $question)
            <div class="border border-gray-300 rounded-md p-4 my-4">
                <p class="font-semibold text-lg text-gray-800">
                    {{ $loop->iteration }}. {{ $question->question_text }} ({{ $question->points }} points)
                </p>
                <ul class="list-decimal ml-6 space-y-2">
                    @foreach($question->answers as $answer)
                        <li class="text-gray-700">
                            {{ $answer->answer_text }}
                            @if($answer->is_correct)
                                <span class="text-green-600 font-semibold">(Correct)</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</div>
@endsection