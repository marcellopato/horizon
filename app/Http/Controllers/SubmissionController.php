<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Interview;
use App\Models\Submission;
use App\Models\SubmissionAnswer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
    public function saveReview(Request $request)
    {
        $request->validate([
            'answer_id' => 'required|exists:submission_answers,id',
            'score' => 'required|integer|min:1|max:10',
            'rating' => 'nullable|in:excellent,good,average,poor',
            'comments' => 'nullable|string|max:1000',
        ]);

        $answer = SubmissionAnswer::findOrFail($request->answer_id);
        
        $answer->update([
            'score' => $request->score,
            'rating' => $request->rating,
            'comments' => $request->comments,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        return back()->with('success', 'Review saved successfully!');
    }

    /**
     * Save overall review for a submission.
     */
    public function saveOverallReview(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|exists:submissions,id',
            'overall_score' => 'required|integer|min:1|max:100',
            'recommendation' => 'nullable|in:strongly_recommend,recommend,neutral,not_recommend,strongly_not_recommend',
            'overall_comments' => 'nullable|string|max:2000',
        ]);

        $submission = Submission::findOrFail($request->submission_id);
        
        $submission->update([
            'overall_score' => $request->overall_score,
            'recommendation' => $request->recommendation,
            'overall_comments' => $request->overall_comments,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
            'status' => 'reviewed',
        ]);

        return back()->with('success', 'Overall review saved successfully!');
    }

    /**
     * Remove the specified submission.
     */
    public function destroy(Submission $submission)
    {
        // Delete associated files
        foreach ($submission->answers as $answer) {
            if ($answer->video_path && Storage::disk('public')->exists($answer->video_path)) {
                Storage::disk('public')->delete($answer->video_path);
            }
        }
        
        $submission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Submission deleted successfully'
        ]);
    }
}
