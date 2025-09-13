<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Interview routes
    Route::get('interviews', [InterviewController::class, 'index'])->name('interviews.index');

    // Protected interview management routes (must come before {interview} routes)
    Route::middleware('manage-interviews')->group(function () {
        Route::get('interviews/create', [InterviewController::class, 'create'])->name('interviews.create');
        Route::post('interviews', [InterviewController::class, 'store'])
            ->name('interviews.store');
        Route::get('interviews/{interview}/edit', [InterviewController::class, 'edit'])->name('interviews.edit');
        Route::put('interviews/{interview}', [InterviewController::class, 'update'])->name('interviews.update');
        Route::delete('interviews/{interview}', [InterviewController::class, 'destroy'])->name('interviews.destroy');
    });

    // Dynamic routes (must come after static routes)
    Route::get('interviews/{interview}', [InterviewController::class, 'show'])
        ->name('interviews.show');

    // Interview start page
    Route::get('interviews/{interview}/start', function (\App\Models\Interview $interview) {
        return view('interviews.start', compact('interview'));
    })
        ->name('interviews.start');

    // Submission routes
    Route::get('submissions', [SubmissionController::class, 'index'])
        ->name('submissions.index')
        ->middleware('can:manage-interviews');
    Route::post('interviews/{interview}/start', [SubmissionController::class, 'start'])
        ->name('submissions.start');
    Route::get('submissions/{submission}/interview', [SubmissionController::class, 'interview'])
        ->name('submissions.interview');
    Route::post('submissions/{submission}/submit', [SubmissionController::class, 'submit'])
        ->name('submissions.submit');
    Route::get('submissions/{submission}/review', [SubmissionController::class, 'review'])
        ->name('submissions.review')
        ->middleware('can:manage-interviews');
    Route::post('submissions/upload-video', [SubmissionController::class, 'uploadVideo'])
        ->name('submissions.upload-video');
    Route::post('submissions/save-review', [SubmissionController::class, 'saveReview'])
        ->name('submissions.save-review')
        ->middleware('can:manage-interviews');
    Route::post('submissions/save-overall-review', [SubmissionController::class, 'saveOverallReview'])
        ->name('submissions.save-overall-review')
        ->middleware('can:manage-interviews');
    // Downloads
    Route::get('submissions/{submission}/download', [SubmissionController::class, 'download'])
        ->name('submissions.download')
        ->middleware('can:manage-interviews');
    Route::get('submission-answers/{answer}/download', [SubmissionController::class, 'downloadAnswer'])
        ->name('submission-answers.download')
        ->middleware('can:manage-interviews');
    Route::delete('submissions/{submission}', [SubmissionController::class, 'destroy'])
        ->name('submissions.destroy')
        ->middleware('can:manage-interviews');
});

require __DIR__.'/auth.php';
