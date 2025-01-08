@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 px-4">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Create a New Test</h1>

    <form action="{{ route('tests.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Test Details -->
        <div class="bg-white shadow-md rounded-md p-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Test Details</h2>
            <div class="mb-4">
                <label for="title" class="block text-gray-600 font-medium mb-2">Test Title</label>
                <input type="text" class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:outline-none" id="title" name="title" required>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-600 font-medium mb-2">Test Description</label>
                <textarea class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:outline-none" id="description" name="description"></textarea>
            </div>

            <div class="mb-4">
                <label for="time_limit" class="block text-gray-600 font-medium mb-2">Time Limit (minutes)</label>
                <input type="number" class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:outline-none" id="time_limit" name="time_limit" value="60" required>
            </div>
        </div>

        <!-- Questions and Answers -->
        <div id="questions-container" class="space-y-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Questions</h2>

            <div class="question bg-white shadow-md rounded-md p-6">
                <div class="mb-4">
                    <label class="block text-gray-600 font-medium mb-2">Question Text</label>
                    <input type="text" class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:outline-none" name="questions[0][text]" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-600 font-medium mb-2">Points</label>
                    <input type="number" class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:outline-none" name="questions[0][points]" placeholder="Points" required>
                </div>

                <div class="answers space-y-4">
                    <h3 class="text-lg font-medium text-gray-700">Answers</h3>
                    <div class="flex items-center space-x-4">
                        <input type="text" class="flex-1 border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:outline-none" name="questions[0][answers][0][text]" placeholder="Answer Text" required>
                        <label class="inline-flex items-center space-x-2">
                            <input type="checkbox" name="questions[0][answers][0][is_correct]" value="1">
                            <span class="text-gray-600">Correct</span>
                        </label>
                    </div>
                    <div class="flex items-center space-x-4">
                        <input type="text" class="flex-1 border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:outline-none" name="questions[0][answers][1][text]" placeholder="Answer Text" required>
                        <label class="inline-flex items-center space-x-2">
                            <input type="checkbox" name="questions[0][answers][1][is_correct]" value="1">
                            <span class="text-gray-600">Correct</span>
                        </label>
                    </div>
                </div>
                <button type="button" class="mt-4 px-4 py-2 bg-blue-500 text-black rounded-md hover:bg-blue-600 add-answer">Add Answer</button>
            </div>
        </div>

        <button type="button" id="add-question" class="px-4 py-2 bg-green-500 text-black rounded-md hover:bg-green-600">Add Question</button>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-black rounded-md hover:bg-blue-600">Create Test</button>
    </form>
</div>

<script>
    function addAnswerButtonHandler(button) {
        button.addEventListener('click', function () {
            const answersContainer = this.previousElementSibling;
            const answerCount = answersContainer.querySelectorAll('.flex').length;
            const questionIndex = this.closest('.question').querySelector('input[type="text"]').name.match(/\d+/)[0];

            const newAnswer = document.createElement('div');
            newAnswer.classList.add('flex', 'items-center', 'space-x-4');
            newAnswer.innerHTML = `
                <input type="text" class="flex-1 border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:outline-none" name="questions[${questionIndex}][answers][${answerCount}][text]" placeholder="Answer Text" required>
                <label class="inline-flex items-center space-x-2">
                    <input type="checkbox" name="questions[${questionIndex}][answers][${answerCount}][is_correct]" value="1">
                    <span class="text-gray-600">Correct</span>
                </label>
            `;
            answersContainer.appendChild(newAnswer);
        });
    }

    document.querySelectorAll('.add-answer').forEach(button => addAnswerButtonHandler(button));

    document.getElementById('add-question').addEventListener('click', function () {
        const questionsContainer = document.getElementById('questions-container');
        const questionCount = document.querySelectorAll('.question').length;

        const newQuestion = document.createElement('div');
        newQuestion.classList.add('question', 'bg-white', 'shadow-md', 'rounded-md', 'p-6');
        newQuestion.innerHTML = `
            <div class="mb-4">
                <label class="block text-gray-600 font-medium mb-2">Question Text</label>
                <input type="text" class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:outline-none" name="questions[${questionCount}][text]" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-600 font-medium mb-2">Points</label>
                <input type="number" class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:outline-none" name="questions[${questionCount}][points]" placeholder="Points" required>
            </div>
            <div class="answers space-y-4">
                <h3 class="text-lg font-medium text-gray-700">Answers</h3>
                <div class="flex items-center space-x-4">
                    <input type="text" class="flex-1 border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:outline-none" name="questions[${questionCount}][answers][0][text]" placeholder="Answer Text" required>
                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" name="questions[${questionCount}][answers][0][is_correct]" value="1">
                        <span class="text-gray-600">Correct</span>
                    </label>
                </div>
                <div class="flex items-center space-x-4">
                    <input type="text" class="flex-1 border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:outline-none" name="questions[${questionCount}][answers][1][text]" placeholder="Answer Text" required>
                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" name="questions[${questionCount}][answers][1][is_correct]" value="1">
                        <span class="text-gray-600">Correct</span>
                    </label>
                </div>
            </div>
            <button type="button" class="mt-4 px-4 py-2 bg-blue-500 text-black rounded-md hover:bg-blue-600 add-answer">Add Answer</button>
        `;

        questionsContainer.appendChild(newQuestion);
        addAnswerButtonHandler(newQuestion.querySelector('.add-answer'));
    });
</script>
@endsection