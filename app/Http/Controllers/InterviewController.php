<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Interview;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;

class InterviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('manage-interviews')->except(['index', 'show']);
    }

    /**
     * Display a listing of the interviews.
     */
    public function index()
    {
        $interviews = Interview::with(['creator', 'questions'])
            ->when(Auth::user()->isCandidate(), function ($query) {
                $query->where('is_active', true);
            })
            ->latest()
            ->paginate(10);

        return view('interviews.index', compact('interviews'));
    }

    /**
     * Show the form for creating a new interview.
     */
    public function create()
    {
        return view('interviews.create');
    }

    /**
     * Store a newly created interview in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'time_limit' => 'nullable|integer|min:1',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.time_limit' => 'nullable|integer|min:1',
        ]);

        $interview = Interview::create([
            'title' => $request->title,
            'description' => $request->description,
            'time_limit' => $request->time_limit,
            'created_by' => Auth::id(),
            'is_active' => $request->boolean('is_active', true),
        ]);

        foreach ($request->questions as $index => $questionData) {
            Question::create([
                'interview_id' => $interview->id,
                'question' => $questionData['question'],
                'order' => $index + 1,
                'time_limit' => $questionData['time_limit'] ?? null,
            ]);
        }

        return redirect()
            ->route('interviews.index')
            ->with('success', 'Interview created successfully!');
    }

    /**
     * Display the specified interview.
     */
    public function show(Interview $interview)
    {
        $interview->load(['creator', 'questions' => function ($query) {
            $query->orderBy('order');
        }]);

        return view('interviews.show', compact('interview'));
    }

    /**
     * Show the form for editing the specified interview.
     */
    public function edit(Interview $interview)
    {
        $interview->load(['questions' => function ($query) {
            $query->orderBy('order');
        }]);

        return view('interviews.edit', compact('interview'));
    }

    /**
     * Update the specified interview in storage.
     */
    public function update(Request $request, Interview $interview)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'time_limit' => 'nullable|integer|min:1',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.time_limit' => 'nullable|integer|min:1',
        ]);

        $interview->update([
            'title' => $request->title,
            'description' => $request->description,
            'time_limit' => $request->time_limit,
            'is_active' => $request->boolean('is_active', $interview->is_active),
        ]);

        // Remove existing questions and add new ones
        $interview->questions()->delete();

        foreach ($request->questions as $index => $questionData) {
            Question::create([
                'interview_id' => $interview->id,
                'question' => $questionData['question'],
                'order' => $index + 1,
                'time_limit' => $questionData['time_limit'] ?? null,
            ]);
        }

        return redirect()
            ->route('interviews.index')
            ->with('success', 'Interview updated successfully!');
    }

    /**
     * Remove the specified interview from storage.
     */
    public function destroy(Interview $interview)
    {
        $interview->delete();

        return redirect()
            ->route('interviews.index')
            ->with('success', 'Interview deleted successfully!');
    }
}
