@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">{{ $test->title }}</h1>
    <form action="{{ route('tests.submit', $test->id) }}" method="POST">
        @csrf

        @foreach($test->questions as $question)
            <div class="mb-6">
                <p class="text-lg font-semibold">{{ $loop->iteration }}. {{ $question->question_text }} ({{ $question->points }} points)</p>
                <ul class="ml-6 mt-2 space-y-2">
                    @foreach($question->answers as $answer)
                        <li>
                            <label class="inline-flex items-center">
                                <input type="radio" name="questions[{{ $question->id }}]" value="{{ $answer->id }}">
                                <span class="ml-2">{{ $answer->answer_text }}</span>
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach

        <button type="submit" class="px-4 py-2 bg-blue-500 text-black rounded-md hover:bg-blue-600">Submit Test</button>
    </form>
</div>
@endsection