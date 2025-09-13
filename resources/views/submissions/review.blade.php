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
                        <span id="submission-status-badge" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ in_array($submission->status, ['completed','submitted','reviewed']) ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
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
                                            <source src="{{ $answer->getVideoUrl() }}" type="{{ $answer->getVideoMimeType() }}">
                                            Your browser does not support the video tag.
                                        </video>
                                        <div class="mt-2 text-sm text-gray-600">
                                            Duration: {{ $answer->getFormattedDuration() }}
                                            @if($answer->getVideoSize())
                                                | Size: {{ number_format($answer->getVideoSize() / 1024 / 1024, 2) }} MB
                                            @endif
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
                                            <button type="submit" class="review-save-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center gap-2">
                                                <svg class="btn-spinner hidden animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                                                <span class="btn-text">Save Review</span>
                                            </button>
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
                    <form id="overall-review-form" action="{{ route('submissions.save-overall-review') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="submission_id" value="{{ $submission->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Overall Score (1-100)</label>
                                <input type="number" name="overall_score" min="1" max="100"
                                       value="{{ old('overall_score', $submission->overall_score ?? '') }}"
                                       placeholder="Enter overall score"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Recommendation</label>
                                <select name="recommendation"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select recommendation...</option>
                                    <option value="strongly_recommend" {{ old('recommendation', $submission->recommendation ?? '') == 'strongly_recommend' ? 'selected' : '' }}>Strongly Recommend</option>
                                    <option value="recommend" {{ old('recommendation', $submission->recommendation ?? '') == 'recommend' ? 'selected' : '' }}>Recommend</option>
                                    <option value="neutral" {{ old('recommendation', $submission->recommendation ?? '') == 'neutral' ? 'selected' : '' }}>Neutral</option>
                                    <option value="not_recommend" {{ old('recommendation', $submission->recommendation ?? '') == 'not_recommend' ? 'selected' : '' }}>Not Recommend</option>
                                    <option value="strongly_not_recommend" {{ old('recommendation', $submission->recommendation ?? '') == 'strongly_not_recommend' ? 'selected' : '' }}>Strongly Not Recommend</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Overall Comments</label>
                            <textarea name="overall_comments" rows="4"
                                      placeholder="Provide overall feedback for this candidate..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('overall_comments', $submission->overall_comments ?? '') }}</textarea>
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('submissions.index') }}" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold">Back to Submissions</a>
                            <button type="submit" id="overall-save-btn" class="px-4 py-2 rounded bg-green-600 hover:bg-green-700 text-white font-semibold inline-flex items-center gap-2">
                                <svg class="btn-spinner hidden animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                                <span class="btn-text">Save Overall Review</span>
                            </button>
                        </div>
                        <div id="overall-review-feedback" class="hidden mt-3 text-sm"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// AJAX para cada review de resposta
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[class^="review-form-"]').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const fd = new FormData(form);
            const btn = form.querySelector('.review-save-btn');
            const spinner = btn?.querySelector('.btn-spinner');
            const btnText = btn?.querySelector('.btn-text');
            try {
                if (btn) {
                    btn.disabled = true;
                    if (spinner) spinner.classList.remove('hidden');
                    if (btnText) btnText.textContent = 'Salvando...';
                }
                const res = await fetch(form.getAttribute('action'), {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: fd,
                });
                const data = await res.json();
                const box = document.createElement('div');
                box.className = 'mt-3 text-sm rounded px-3 py-2 ' + (data.success ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700');
                box.textContent = data.message || (data.success ? 'Saved!' : 'Failed to save');
                form.appendChild(box);
                setTimeout(() => box.remove(), 3000);
            } catch (err) {
                console.error('Save review error:', err);
            } finally {
                if (btn) {
                    btn.disabled = false;
                    if (spinner) spinner.classList.add('hidden');
                    if (btnText) btnText.textContent = 'Save Review';
                }
            }
        });
    });

    // AJAX para Overall Review
    const overallForm = document.getElementById('overall-review-form');
    const feedback = document.getElementById('overall-review-feedback');
    overallForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const fd = new FormData(overallForm);
        const btn = document.getElementById('overall-save-btn');
        const spinner = btn?.querySelector('.btn-spinner');
        const btnText = btn?.querySelector('.btn-text');
        try {
            if (btn) {
                btn.disabled = true;
                if (spinner) spinner.classList.remove('hidden');
                if (btnText) btnText.textContent = 'Salvando...';
            }
            const res = await fetch(overallForm.getAttribute('action'), {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: fd,
            });
            const data = await res.json();
            feedback.className = 'mt-3 text-sm rounded px-3 py-2 ' + (data.success ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700');
            feedback.textContent = data.message || (data.success ? 'Saved!' : 'Failed to save');
            feedback.classList.remove('hidden');
            setTimeout(() => feedback.classList.add('hidden'), 3000);

            // If status returned as reviewed, update badge UI
            if (data?.submission?.status === 'reviewed') {
                const badge = document.getElementById('submission-status-badge');
                if (badge) {
                    badge.textContent = 'Reviewed';
                    badge.classList.remove('bg-yellow-100','text-yellow-800');
                    badge.classList.add('bg-green-100','text-green-800');
                }
            }
        } catch (err) {
            console.error('Save overall review error:', err);
        } finally {
            if (btn) {
                btn.disabled = false;
                if (spinner) spinner.classList.add('hidden');
                if (btnText) btnText.textContent = 'Save Overall Review';
            }
        }
    });
});
</script>

<style>
/* TailwindCSS handles most styling, minimal custom CSS needed */
.video-container video {
    object-fit: cover;
}
</style>
@endsection