@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4>{{ $interview->title }}</h4>
                        <div>
                            <a href="{{ route('interviews.index') }}" class="btn btn-secondary me-2">
                                Back to Interviews
                            </a>
                            @if(auth()->user()->canManageInterviews())
                                <a href="{{ route('interviews.edit', $interview) }}" class="btn btn-primary">
                                    Edit Interview
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h5>Description</h5>
                            <p class="text-muted">{{ $interview->description }}</p>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6>Interview Details</h6>
                                    <p class="mb-1">
                                        <strong>Status:</strong> 
                                        <span class="badge {{ $interview->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $interview->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </p>
                                    <p class="mb-1">
                                        <strong>Created by:</strong> {{ $interview->creator->name }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Created at:</strong> {{ $interview->created_at->format('M d, Y H:i') }}
                                    </p>
                                    @if($interview->time_limit)
                                        <p class="mb-1">
                                            <strong>Time limit:</strong> {{ $interview->time_limit }} minutes
                                        </p>
                                    @endif
                                    <p class="mb-0">
                                        <strong>Total questions:</strong> {{ $interview->questions->count() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <h5 class="mb-3">Questions</h5>
                        
                        @if($interview->questions->count() > 0)
                            @foreach($interview->questions as $question)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="card-title">
                                                    Question {{ $question->order }}
                                                </h6>
                                                <p class="card-text">{{ $question->question }}</p>
                                            </div>
                                            @if($question->time_limit)
                                                <div class="ms-3">
                                                    <span class="badge bg-info">
                                                        {{ $question->time_limit }} min
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <p class="text-muted">No questions added to this interview yet.</p>
                            </div>
                        @endif
                    </div>

                    @if(auth()->user()->isCandidate() && $interview->is_active)
                        <hr>
                        <div class="text-center">
                            <h5 class="mb-3">Ready to start?</h5>
                            <p class="text-muted mb-3">
                                This interview has {{ $interview->questions->count() }} questions.
                                @if($interview->time_limit)
                                    You have {{ $interview->time_limit }} minutes to complete all questions.
                                @endif
                            </p>
                            <a href="#" class="btn btn-success btn-lg">
                                Start Interview
                            </a>
                            <div class="mt-2">
                                <small class="text-muted">
                                    Make sure you have a working camera and microphone before starting.
                                </small>
                            </div>
                        </div>
                    @elseif(auth()->user()->isCandidate() && !$interview->is_active)
                        <div class="alert alert-warning">
                            <strong>Interview Not Available:</strong> This interview is currently inactive.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection