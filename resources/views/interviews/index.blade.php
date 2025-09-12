@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h4 class="text-xl font-semibold text-gray-800">Interviews</h4>
            @if(auth()->user()->canManageInterviews())
                <a href="{{ route('interviews.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create New Interview
                </a>
            @endif
        </div>

        <div class="p-6">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($interviews->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Questions</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($interviews as $interview)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $interview->title }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ Str::limit($interview->description, 50) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $interview->questions_count ?? $interview->questions->count() }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $interview->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ $interview->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $interview->creator->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $interview->created_at->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('interviews.show', $interview) }}" 
                                                       class="text-blue-600 hover:text-blue-900 px-3 py-1 border border-blue-600 rounded text-xs hover:bg-blue-50">
                                                        View
                                                    </a>
                                                    
                                                    @if(auth()->user()->isCandidate())
                                                        <a href="{{ route('interviews.start', $interview) }}" 
                                                           class="bg-green-500 hover:bg-green-700 text-white px-3 py-1 rounded text-xs">
                                                            Start Interview
                                                        </a>
                                                    @endif
                                                    
                                                    @if(auth()->user()->canManageInterviews())
                                                        <a href="{{ route('interviews.edit', $interview) }}" 
                                                           class="text-gray-600 hover:text-gray-900 px-3 py-1 border border-gray-600 rounded text-xs hover:bg-gray-50">
                                                            Edit
                                                        </a>
                                                        
                                                        <form action="{{ route('interviews.destroy', $interview) }}" 
                                                              method="POST" 
                                                              class="inline"
                                                              onsubmit="return confirm('Are you sure you want to delete this interview?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900 px-3 py-1 border border-red-600 rounded text-xs hover:bg-red-50">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-center mt-4">
                            {{ $interviews->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <h5 class="text-lg font-medium text-gray-900 mb-2">No interviews found</h5>
                            <p class="text-gray-500">
                                @if(auth()->user()->canManageInterviews())
                                    Get started by creating your first interview.
                                @else
                                    There are no active interviews available at the moment.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
    </div>
</div>
@endsection