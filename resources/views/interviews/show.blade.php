@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h4 class="text-xl font-semibold text-gray-800">{{ $interview->title }}</h4>
                <div class="space-x-2">
                    <a href="{{ route('interviews.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Back to Interviews
                    </a>
                    @if(auth()->user()->canManageInterviews())
                        <a href="{{ route('interviews.edit', $interview) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Interview
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-6">
                    <div class="grid md:grid-cols-3 gap-6 mb-6">
                        <div class="md:col-span-2">
                            <h5 class="text-lg font-medium text-gray-900 mb-3">Description</h5>
                            <p class="text-gray-600">{{ $interview->description }}</p>
                        </div>
                        <div class="md:col-span-1">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h6 class="text-base font-medium text-gray-900 mb-3">Interview Details</h6>
                                <div class="space-y-2">
                                    <p class="text-sm">
                                        <span class="font-medium text-gray-700">Status:</span> 
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $interview->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $interview->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </p>
                                    <p class="text-sm">
                                        <span class="font-medium text-gray-700">Created by:</span> <span class="text-gray-600">{{ $interview->creator->name }}</span>
                                    </p>
                                    <p class="text-sm">
                                        <span class="font-medium text-gray-700">Created at:</span> <span class="text-gray-600">{{ $interview->created_at->format('M d, Y H:i') }}</span>
                                    </p>
                                    @if($interview->time_limit)
                                        <p class="text-sm">
                                            <span class="font-medium text-gray-700">Time limit:</span> <span class="text-gray-600">{{ $interview->time_limit }} minutes</span>
                                        </p>
                                    @endif                    <hr>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h5 class="text-lg font-medium text-gray-900 mb-4">Questions</h5>
                        
                        @if($interview->questions->count() > 0)
                            <div class="space-y-4">
                                @foreach($interview->questions as $question)
                                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-grow">
                                                <h6 class="text-base font-medium text-gray-900 mb-2">
                                                    Question {{ $question->order }}
                                                </h6>
                                                <p class="text-gray-600">{{ $question->question }}</p>
                                            </div>
                                            @if($question->time_limit)
                                                <div class="ml-4">
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        {{ $question->time_limit }} min
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500">No questions added to this interview yet.</p>
                            </div>
                        @endif
                    </div>

                    @if(auth()->user()->isCandidate() && $interview->is_active)
                        <div class="border-t border-gray-200 pt-6">
                            <div class="text-center">
                                <h5 class="text-lg font-medium text-gray-900 mb-3">Ready to start?</h5>
                                <p class="text-gray-600 mb-4">
                                    This interview has {{ $interview->questions->count() }} questions.
                                    @if($interview->time_limit)
                                        You have {{ $interview->time_limit }} minutes to complete all questions.
                                    @endif
                                </p>
                                <a href="{{ route('interviews.start', $interview) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-lg">
                                    Start Interview
                                </a>
                                <div class="mt-3">
                                    <p class="text-sm text-gray-500">
                                        Make sure you have a working camera and microphone before starting.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @elseif(auth()->user()->isCandidate() && !$interview->is_active)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <div class="flex">
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">
                                        Interview Not Available
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>This interview is currently inactive.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
    </div>
</div>
@endsection