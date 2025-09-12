@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-2xl font-bold text-gray-900">Review Submission</h4>
                        <div class="text-gray-600 mt-1">
                            {{ $submission->interview->title }} - {{ $submission->user->name }}
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $submission->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($submission->status) }}
                        </span>
                        <div class="w-24 bg-gray-200 rounded-full h-5">
                            <div class="bg-blue-500 h-5 rounded-full flex items-center justify-center text-xs text-white font-medium" style="width: {{ $submission->getProgressPercentage() }}%">
                                {{ $submission->getProgressPercentage() }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <span class="font-semibold text-gray-900">Candidate:</span> 
                        <span class="text-gray-700">{{ $submission->user->name }} ({{ $submission->user->email }})</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-900">Submitted:</span> 
                        @if($submission->submitted_at)
                            <span class="text-gray-700">{{ $submission->submitted_at->format('M d, Y H:i') }}</span>
                        @else
                            <span class="text-gray-500">Not submitted yet</span>
                        @endif
                    </div>
                </div>
            </div>
        
        <!-- Questions and Answers -->
        @foreach($submission->interview->questions as $question)
            @php
                $answer = $submission->answers()->where('question_id', $question->id)->first();
            @endphp
            
            <div class="bg-white rounded-lg shadow-lg mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h5 class="text-xl font-bold text-gray-900">Question {{ $question->order }}</h5>
                        <div class="flex items-center space-x-3">
                            @if($question->time_limit)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">{{ $question->time_limit }} min limit</span>
                            @endif
                            @if($answer && $answer->isCompleted())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Answered</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">Not answered</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="px-6 py-6">
                    <div>
                        <h6 class="text-lg font-semibold text-gray-900 mb-2">Question:</h6>
                        <p class="text-gray-700 mb-6">{{ $question->question }}</p>
                                
                        @if($answer && $answer->isCompleted())
                            <h6 class="text-lg font-semibold text-gray-900 mb-3">Answer:</h6>
                            <div class="answer-section">
                                @if($answer->hasVideo())
                                    <div class="video-container mb-6">
                                        <video controls class="w-full rounded-lg" style="max-height: 400px;">
                                            <source src="{{ $answer->getVideoUrl() }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                        <div class="mt-2 text-sm text-gray-600">
                                            Duration: {{ $answer->getFormattedDuration() }} | 
                                            Size: {{ $answer->getVideoSize() }}
                                        </div>
                                    </div>
                                @endif
                                        
                                <!-- Review Form -->
                                <div class="review-form bg-gray-50 rounded-lg p-6 mt-4">
                                    <form action="{{ route('submissions.save-review') }}" method="POST" class="review-form-{{ $answer->id }}">
                                        @csrf
                                        <input type="hidden" name="answer_id" value="{{ $answer->id }}">
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Score (1-10)</label>
                                                <select name="score" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                                    <option value="">Select score...</option>
                                                    @for($i = 1; $i <= 10; $i++)
                                                        <option value="{{ $i }}" 
                                                                {{ old('score', $answer->score ?? '') == $i ? 'selected' : '' }}>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                                                <select name="rating" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                    <option value="">Select rating...</option>
                                                    <option value="excellent" {{ old('rating', $answer->rating ?? '') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                                                    <option value="good" {{ old('rating', $answer->rating ?? '') == 'good' ? 'selected' : '' }}>Good</option>
                                                    <option value="average" {{ old('rating', $answer->rating ?? '') == 'average' ? 'selected' : '' }}>Average</option>
                                                    <option value="poor" {{ old('rating', $answer->rating ?? '') == 'poor' ? 'selected' : '' }}>Poor</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-6">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Comments</label>
                                            <textarea name="comments" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" rows="3" 
                                                      placeholder="Add your review comments...">{{ old('comments', $answer->comments ?? '') }}</textarea>
                                        </div>
                                        
                                        <div class="mt-6">
                                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save Review</button>
                                        </div>
                                    </form>
                                </div>
                                            </div>
                        @else
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-blue-800">This question has not been answered yet.</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Overall Review -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-xl font-bold text-gray-900">Overall Review</h5>
            </div>
            <div class="px-6 py-6">
                    <form action="{{ route('submissions.save-overall-review') }}" method="POST">
                        @csrf
                        <input type="hidden" name="submission_id" value="{{ $submission->id }}">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Overall Score (1-100)</label>
                                    <input type="number" name="overall_score" class="form-control" 
                                           min="1" max="100" 
                                           value="{{ old('overall_score', $submission->overall_score ?? '') }}"
                                           placeholder="Enter overall score">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Recommendation</label>
                                    <select name="recommendation" class="form-select">
                                        <option value="">Select recommendation...</option>
                                        <option value="strongly_recommend" {{ old('recommendation', $submission->recommendation ?? '') == 'strongly_recommend' ? 'selected' : '' }}>Strongly Recommend</option>
                                        <option value="recommend" {{ old('recommendation', $submission->recommendation ?? '') == 'recommend' ? 'selected' : '' }}>Recommend</option>
                                        <option value="neutral" {{ old('recommendation', $submission->recommendation ?? '') == 'neutral' ? 'selected' : '' }}>Neutral</option>
                                        <option value="not_recommend" {{ old('recommendation', $submission->recommendation ?? '') == 'not_recommend' ? 'selected' : '' }}>Not Recommend</option>
                                        <option value="strongly_not_recommend" {{ old('recommendation', $submission->recommendation ?? '') == 'strongly_not_recommend' ? 'selected' : '' }}>Strongly Not Recommend</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Overall Comments</label>
                            <textarea name="overall_comments" class="form-control" rows="4" 
                                      placeholder="Provide overall feedback for this candidate...">{{ old('overall_comments', $submission->overall_comments ?? '') }}</textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('submissions.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Submissions
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Save Overall Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* TailwindCSS handles most styling, minimal custom CSS needed */
.video-container video {
    object-fit: cover;
}
</style>
@endsection