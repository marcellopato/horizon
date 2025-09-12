@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Interviews</h4>
                    @if(auth()->user()->canManageInterviews())
                        <a href="{{ route('interviews.create') }}" class="btn btn-primary">
                            Create New Interview
                        </a>
                    @endif
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($interviews->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Questions</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($interviews as $interview)
                                        <tr>
                                            <td>{{ $interview->title }}</td>
                                            <td>{{ Str::limit($interview->description, 50) }}</td>
                                            <td>{{ $interview->questions_count ?? $interview->questions->count() }}</td>
                                            <td>
                                                <span class="badge {{ $interview->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $interview->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>{{ $interview->creator->name }}</td>
                                            <td>{{ $interview->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('interviews.show', $interview) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        View
                                                    </a>
                                                    
                                                    @if(auth()->user()->canManageInterviews())
                                                        <a href="{{ route('interviews.edit', $interview) }}" 
                                                           class="btn btn-sm btn-outline-secondary">
                                                            Edit
                                                        </a>
                                                        
                                                        <form action="{{ route('interviews.destroy', $interview) }}" 
                                                              method="POST" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Are you sure you want to delete this interview?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
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

                        <div class="d-flex justify-content-center">
                            {{ $interviews->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <h5>No interviews found</h5>
                            <p class="text-muted">
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
    </div>
</div>
@endsection