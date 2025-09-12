@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4>Create New Interview</h4>
                        <a href="{{ route('interviews.index') }}" class="btn btn-secondary">
                            Back to Interviews
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('interviews.store') }}" id="interview-form">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Interview Title <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="time_limit" class="form-label">Interview Time Limit (minutes)</label>
                            <input type="number" 
                                   class="form-control @error('time_limit') is-invalid @enderror" 
                                   id="time_limit" 
                                   name="time_limit" 
                                   value="{{ old('time_limit') }}" 
                                   min="1">
                            <div class="form-text">Leave empty for no time limit</div>
                            @error('time_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="is_active" 
                                       id="is_active" 
                                       value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active Interview
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Questions <span class="text-danger">*</span></h5>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="add-question">
                                    Add Question
                                </button>
                            </div>

                            <div id="questions-container">
                                @if(old('questions'))
                                    @foreach(old('questions') as $index => $question)
                                        <div class="question-item border rounded p-3 mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6>Question {{ $index + 1 }}</h6>
                                                <button type="button" class="btn btn-outline-danger btn-sm remove-question">
                                                    Remove
                                                </button>
                                            </div>
                                            
                                            <div class="mb-2">
                                                <label class="form-label">Question Text <span class="text-danger">*</span></label>
                                                <textarea class="form-control @error('questions.' . $index . '.question') is-invalid @enderror" 
                                                          name="questions[{{ $index }}][question]" 
                                                          rows="2" 
                                                          required>{{ $question['question'] ?? '' }}</textarea>
                                                @error('questions.' . $index . '.question')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="mb-0">
                                                <label class="form-label">Time Limit (minutes)</label>
                                                <input type="number" 
                                                       class="form-control @error('questions.' . $index . '.time_limit') is-invalid @enderror" 
                                                       name="questions[{{ $index }}][time_limit]" 
                                                       value="{{ $question['time_limit'] ?? '' }}" 
                                                       min="1">
                                                @error('questions.' . $index . '.time_limit')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="question-item border rounded p-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6>Question 1</h6>
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-question">
                                                Remove
                                            </button>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <label class="form-label">Question Text <span class="text-danger">*</span></label>
                                            <textarea class="form-control" 
                                                      name="questions[0][question]" 
                                                      rows="2" 
                                                      required></textarea>
                                        </div>
                                        
                                        <div class="mb-0">
                                            <label class="form-label">Time Limit (minutes)</label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   name="questions[0][time_limit]" 
                                                   min="1">
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @error('questions')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Create Interview
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let questionIndex = {{ old('questions') ? count(old('questions')) : 1 }};
    
    document.getElementById('add-question').addEventListener('click', function() {
        const container = document.getElementById('questions-container');
        const questionItem = document.createElement('div');
        questionItem.className = 'question-item border rounded p-3 mb-3';
        questionItem.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6>Question ${questionIndex + 1}</h6>
                <button type="button" class="btn btn-outline-danger btn-sm remove-question">
                    Remove
                </button>
            </div>
            
            <div class="mb-2">
                <label class="form-label">Question Text <span class="text-danger">*</span></label>
                <textarea class="form-control" 
                          name="questions[${questionIndex}][question]" 
                          rows="2" 
                          required></textarea>
            </div>
            
            <div class="mb-0">
                <label class="form-label">Time Limit (minutes)</label>
                <input type="number" 
                       class="form-control" 
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
            question.querySelector('h6').textContent = `Question ${index + 1}`;
        });
    }
});
</script>
@endsection