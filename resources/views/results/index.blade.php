@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Your Results</h1>

    @if($submissions->isEmpty())
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
            <p>You have not submitted any tests yet.</p>
        </div>
    @else
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full table-auto border-collapse border border-gray-200">
                <thead class="bg-gray-100 text-gray-700 text-left">
                    <tr>
                        <th class="px-6 py-3 border border-gray-200">Test Title</th>
                        <th class="px-6 py-3 border border-gray-200">Score</th>
                        <th class="px-6 py-3 border border-gray-200">Submitted At</th>
                        <th class="px-6 py-3 border border-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600">
                    @foreach($submissions as $submission)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 border border-gray-200 font-medium">
                                {{ $submission->test->title }}
                            </td>
                            <td class="px-6 py-4 border border-gray-200 text-center">
                                {{ $submission->score }} / {{ $submission->test->questions->sum('points') }}
                            </td>
                            <td class="px-6 py-4 border border-gray-200"> 
                                {{ gmdate('H:i:s', $submission->time_taken ?? $submission->submitted_at->diffInSeconds($submission->started_at ?? now())) }} 
                            </td>
                            <td class="px-6 py-4 border border-gray-200">
                                <a href="{{ route('tests.show', $submission->test->id) }}" class="text-blue-500 hover:underline">View Test</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection