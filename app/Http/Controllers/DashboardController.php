<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Handles post-login landing experience.
 * - Candidates: redirect to interviews list
 * - Admins/Reviewers: render dashboard with quick links and stats
 */
class DashboardController extends Controller
{
    /**
     * Display dashboard or redirect based on role.
     */
    public function index(): RedirectResponse|View
    {
        $user = Auth::user();

        $isCandidate = $user && (
            (method_exists($user, 'isCandidate') && $user->isCandidate())
            || ($user->role ?? null) === 'candidate'
        );

        if ($isCandidate) {
            return redirect()->route('interviews.index');
        }

        // Basic stats for admins/reviewers
        $stats = [
            'interviews_count' => Interview::count(),
            // Pending = not reviewed yet
            'pending_reviews_count' => Submission::query()
                ->whereIn('status', ['submitted', 'completed'])
                ->count(),
            'reviewed_count' => Submission::query()
                ->where('status', 'reviewed')
                ->count(),
        ];

        return view('dashboard', compact('stats'));
    }
}
