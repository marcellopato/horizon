@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h4 class="text-xl font-semibold text-gray-800">Interview Submissions</h4>
            <div class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">{{ $submissions->count() }} Submissions</div>
        </div>

        <div class="p-6">
                    @if (session('success'))
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4" role="alert">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-green-800">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if($submissions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidate</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Interview</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($submissions as $submission)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                                        {{ strtoupper(substr($submission->user->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-semibold text-gray-900">{{ $submission->user->name }}</div>
                                                        <div class="text-gray-500 text-sm">{{ $submission->user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="font-semibold text-gray-900">{{ $submission->interview->title }}</div>
                                                <div class="text-gray-500 text-sm">
                                                    {{ $submission->interview->questions->count() }} questions
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($submission->status === 'completed')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                                                @elseif($submission->status === 'in_progress')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">In Progress</span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($submission->status) }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="w-full bg-gray-200 rounded-full h-5">
                                                    <div class="bg-blue-500 h-5 rounded-full flex items-center justify-center text-xs text-white font-medium" 
                                                         style="width: {{ $submission->getProgressPercentage() }}%">
                                                        {{ $submission->getProgressPercentage() }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($submission->submitted_at)
                                                    {{ $submission->submitted_at->format('M d, Y H:i') }}
                                                @else
                                                    <span class="text-gray-500">Not submitted</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('submissions.review', $submission) }}" 
                                                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                        Review
                                                    </a>
                                                    
                                                    @if($submission->isCompleted())
                                                        <a href="{{ route('submissions.download', $submission) }}"
                                                           class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-1 px-3 rounded text-sm">
                                                            Download
                                                        </a>
                                                    @endif
                                                    
                                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm" 
                                                            onclick="deleteSubmission({{ $submission->id }})">
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <h5 class="mt-3">No Submissions Yet</h5>
                            <p class="text-muted">Submissions will appear here once candidates start taking interviews.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteSubmission(submissionId) {
    if (confirm('Are you sure you want to delete this submission? This action cannot be undone.')) {
        fetch(`/submissions/${submissionId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to delete submission');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to delete submission');
        });
    }
}
</script>

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    background: #007bff;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.progress {
    background-color: #e9ecef;
}
</style>
@endsection