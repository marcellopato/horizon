<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Interview;
use App\Models\Submission;
use App\Models\SubmissionAnswer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SubmissionController extends Controller
{
    /**
     * Start an interview for a candidate.
     */
    public function start(Interview $interview)
    {
        if (!$interview->is_active) {
            return redirect()->route('interviews.index')
                ->with('error', 'This interview is not currently available.');
        }

        $submission = Submission::firstOrCreate([
            'user_id' => Auth::id(),
            'interview_id' => $interview->id,
        ], [
            'status' => 'in_progress',
            'started_at' => now(),
            'metadata' => [
                'user_agent' => request()->userAgent(),
                'ip_address' => request()->ip(),
            ],
        ]);

        return redirect()->route('submissions.interview', $submission);
    }

    /**
     * Show the interview interface for a submission.
     */
    public function interview(Submission $submission)
    {
        if ($submission->user_id !== Auth::id()) {
            abort(403, 'Access denied.');
        }

        $submission->load(['interview.questions', 'answers']);
        
        return view('submissions.interview', compact('submission'));
    }

    /**
     * Upload video answer for a question.
     */
    public function uploadVideo(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|exists:submissions,id',
            'question_id' => 'required|exists:questions,id',
            'video' => 'required|file|mimes:mp4,webm,mov|max:50000', // 50MB max
            'duration' => 'required|integer|min:1',
        ]);

        $submission = Submission::findOrFail($request->submission_id);
        
        if ($submission->user_id !== Auth::id()) {
            abort(403, 'Access denied.');
        }

        $videoPath = $request->file('video')->store(
            "interviews/{$submission->interview_id}/submissions/{$submission->id}",
            'public'
        );

        $answer = SubmissionAnswer::updateOrCreate([
            'submission_id' => $submission->id,
            'question_id' => $request->question_id,
        ], [
            'video_path' => $videoPath,
            'recording_duration' => $request->duration,
            'status' => 'completed',
            'completed_at' => now(),
            'attempts' => $request->input('attempts', 1),
            'metadata' => [
                'file_size' => $request->file('video')->getSize(),
                'mime_type' => $request->file('video')->getMimeType(),
                'uploaded_at' => now()->toISOString(),
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Video uploaded successfully',
            'answer' => $answer,
        ]);
    }

    /**
     * Mark submission as completed by the candidate.
     */
    public function submit(Submission $submission, Request $request)
    {
        if ($submission->user_id !== Auth::id()) {
            abort(403, 'Access denied.');
        }

        // Ensure all questions have a completed answer
        $totalQuestions = $submission->interview->questions()->count();
        $completedAnswers = $submission->answers()->where('status', 'completed')->count();

        if ($completedAnswers < $totalQuestions) {
            $msg = 'You must answer all questions before submitting.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $submission->update([
            'status' => 'completed',
            'completed_at' => now(),
            'total_time_spent' => $submission->answers()->sum('recording_duration') ?? null,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Submission completed']);
        }

        return redirect()->route('interviews.index')->with('success', 'Interview submitted successfully!');
    }

    /**
     * Display a listing of submissions for admin/reviewers.
     */
    public function index()
    {
        $submissions = Submission::with(['user', 'interview.questions', 'answers'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('submissions.index', compact('submissions'));
    }

    /**
     * Show the review page for a submission.
     */
    public function review(Submission $submission)
    {
        $submission->load(['user', 'interview.questions', 'answers']);
        
        return view('submissions.review', compact('submission'));
    }

    /**
     * Save review for a specific answer.
     */
    /**
     * Save review for a specific answer.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function saveReview(Request $request)
    {
        $request->validate(
            [
                'answer_id' => 'required|exists:submission_answers,id',
                'score' => 'required|integer|min:1|max:10',
                'rating' => ['nullable', Rule::in(['excellent', 'good', 'average', 'poor'])],
                'comments' => 'nullable|string|max:1000',
            ]
        );

        $answer = SubmissionAnswer::findOrFail($request->answer_id);
        
        $answer->update(
            [
                'score' => $request->score,
                'rating' => $request->rating,
                'comments' => $request->comments,
                'reviewed_at' => now(),
                'reviewed_by' => Auth::id(),
            ]
        );

        if ($request->expectsJson()) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Review saved successfully!',
                    'answer' => $answer->only([
                        'id', 'score', 'rating', 'comments', 'reviewed_at', 'reviewed_by',
                    ]),
                ]
            );
        }

        return back()->with('success', 'Review saved successfully!');
    }

    /**
     * Save overall review for a submission.
     */
    /**
     * Save overall review for a submission.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function saveOverallReview(Request $request)
    {
        $request->validate(
            [
                'submission_id' => 'required|exists:submissions,id',
                'overall_score' => 'required|integer|min:1|max:100',
                'recommendation' => ['nullable', Rule::in([
                    'strongly_recommend', 'recommend', 'neutral', 'not_recommend', 'strongly_not_recommend',
                ])],
                'overall_comments' => 'nullable|string|max:2000',
            ]
        );

        $submission = Submission::findOrFail($request->submission_id);
        
        $submission->update(
            [
                'overall_score' => $request->overall_score,
                'recommendation' => $request->recommendation,
                'overall_comments' => $request->overall_comments,
                'reviewed_at' => now(),
                'reviewed_by' => Auth::id(),
                'status' => 'reviewed',
            ]
        );

        if ($request->expectsJson()) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Overall review saved successfully!',
                    'submission' => $submission->only([
                        'id', 'overall_score', 'recommendation', 'overall_comments', 'reviewed_at', 'reviewed_by', 'status',
                    ]),
                ]
            );
        }

        return back()->with('success', 'Overall review saved successfully!');
    }

    /**
     * Remove the specified submission.
     */
    /**
     * Remove the specified submission.
     *
     * @param Submission $submission
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Submission $submission)
    {
        // Delete associated files
        foreach ($submission->answers as $answer) {
            $path = $answer->video_path;
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
        
        $submission->delete();

        return response()->json(
            [
                'success' => true,
                'message' => 'Submission deleted successfully',
            ]
        );
    }
}
