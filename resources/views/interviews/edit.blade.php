@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h4 class="text-xl font-semibold text-gray-800">Edit Interview</h4>
                <a href="{{ route('interviews.show', $interview) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Interview
                </a>
            </div>
        </div>

        <div class="p-6">
                    <form method="POST" action="{{ route('interviews.update', $interview) }}" id="interview-form">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700">Interview Title <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   class="mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('title') border-red-500 @else border-gray-300 @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $interview->title) }}" 
                                   required>
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description <span class="text-red-500">*</span></label>
                            <textarea class="mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-500 @else border-gray-300 @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      required>{{ old('description', $interview->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="time_limit" class="block text-sm font-medium text-gray-700">Interview Time Limit (minutes)</label>
                            <input type="number" 
                                   class="mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('time_limit') border-red-500 @else border-gray-300 @enderror" 
                                   id="time_limit" 
                                   name="time_limit" 
                                   value="{{ old('time_limit', $interview->time_limit) }}" 
                                   min="1">
                            <p class="mt-1 text-sm text-gray-500">Leave empty for no time limit</p>
                            @error('time_limit')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center">
                                <input class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" 
                                       type="checkbox" 
                                       name="is_active" 
                                       id="is_active" 
                                       value="1" 
                                       {{ old('is_active', $interview->is_active) ? 'checked' : '' }}>
                                <label class="ml-2 block text-sm text-gray-900" for="is_active">
                                    Active Interview
                                </label>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6 mt-6">

                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h5 class="text-lg font-medium text-gray-900">Questions <span class="text-red-500">*</span></h5>
                                <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm" id="add-question">
                                    Add Question
                                </button>
                            </div>

                            <div id="questions-container">
                                @if(old('questions'))
                                    @foreach(old('questions') as $index => $question)
                                        <div class="question-item border border-gray-300 rounded-lg p-4 mb-4 bg-gray-50">
                                            <div class="flex justify-between items-center mb-3">
                                                <h6 class="text-lg font-medium text-gray-900">Question {{ $index + 1 }}</h6>
                                                <button type="button" class="text-red-600 hover:text-red-900 px-3 py-1 border border-red-600 rounded text-sm hover:bg-red-50 remove-question">
                                                    Remove
                                                </button>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="block text-sm font-medium text-gray-700">Question Text <span class="text-red-500">*</span></label>
                                                <textarea class="mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('questions.' . $index . '.question') border-red-500 @else border-gray-300 @enderror" 
                                                          name="questions[{{ $index }}][question]" 
                                                          rows="2" 
                                                          required>{{ $question['question'] ?? '' }}</textarea>
                                                @error('questions.' . $index . '.question')
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            
                                            <div class="mb-0">
                                                <label class="block text-sm font-medium text-gray-700">Time Limit (minutes)</label>
                                                <input type="number" 
                                                       class="mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('questions.' . $index . '.time_limit') border-red-500 @else border-gray-300 @enderror" 
                                                       name="questions[{{ $index }}][time_limit]" 
                                                       value="{{ $question['time_limit'] ?? '' }}" 
                                                       min="1">
                                                @error('questions.' . $index . '.time_limit')
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    @foreach($interview->questions as $index => $question)
                                        <div class="question-item border border-gray-300 rounded-lg p-4 mb-4 bg-gray-50">
                                            <div class="flex justify-between items-center mb-3">
                                                <h6 class="text-lg font-medium text-gray-900">Question {{ $index + 1 }}</h6>
                                                <button type="button" class="text-red-600 hover:text-red-900 px-3 py-1 border border-red-600 rounded text-sm hover:bg-red-50 remove-question">
                                                    Remove
                                                </button>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="block text-sm font-medium text-gray-700">Question Text <span class="text-red-500">*</span></label>
                                                <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                                          name="questions[{{ $index }}][question]" 
                                                          rows="2" 
                                                          required>{{ $question->question }}</textarea>
                                            </div>
                                            
                                            <div class="mb-0">
                                                <label class="block text-sm font-medium text-gray-700">Time Limit (minutes)</label>
                                                <input type="number" 
                                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                                       name="questions[{{ $index }}][time_limit]" 
                                                       value="{{ $question->time_limit }}" 
                                                       min="1">
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            @error('questions')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-3 pt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                Update Interview
                            </button>
                        </div>
                        </div>
                    </form>
                </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let questionIndex = {{ old('questions') ? count(old('questions')) : $interview->questions->count() }};
    
    document.getElementById('add-question').addEventListener('click', function() {
        const container = document.getElementById('questions-container');
        const questionItem = document.createElement('div');
        questionItem.className = 'question-item border border-gray-300 rounded-lg p-4 mb-4 bg-gray-50';
        questionItem.innerHTML = `
            <div class="flex justify-between items-center mb-3">
                <h6 class="text-lg font-medium text-gray-900">Question ${questionIndex + 1}</h6>
                <button type="button" class="text-red-600 hover:text-red-900 px-3 py-1 border border-red-600 rounded text-sm hover:bg-red-50 remove-question">
                    Remove
                </button>
            </div>
            
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Question Text <span class="text-red-500">*</span></label>
                <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                          name="questions[${questionIndex}][question]" 
                          rows="2" 
                          required></textarea>
            </div>
            
            <div class="mb-0">
                <label class="block text-sm font-medium text-gray-700">Time Limit (minutes)</label>
                <input type="number" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                       name="questions[${questionIndex}][time_limit]" 
                       min="1">
            </div>
        `;
        container.appendChild(questionItem);
        questionIndex++;
        updateQuestionNumbers();
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-question')) {
            const questionItem = e.target.closest('.question-item');
            if (document.querySelectorAll('.question-item').length > 1) {
                questionItem.remove();
                updateQuestionNumbers();
            } else {
                alert('You must have at least one question.');
            }
        }
    });
    
    function updateQuestionNumbers() {
        const questions = document.querySelectorAll('.question-item');
        questions.forEach((question, index) => {
            const h6 = question.querySelector('h6');
            if (h6) {
                h6.textContent = `Question ${index + 1}`;
            }
        });
    }
});
</script>
@endsection