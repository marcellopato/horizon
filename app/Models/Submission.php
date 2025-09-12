<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    protected $fillable = [
        'user_id',
        'interview_id',
        'status',
        'started_at',
        'completed_at',
        'submitted_at',
        'total_time_spent',
        'metadata',
        'overall_score',
        'recommendation',
        'overall_comments',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(SubmissionAnswer::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed' || $this->status === 'submitted';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function getProgressPercentage(): int
    {
        $totalQuestions = $this->interview->questions()->count();
        $completedAnswers = $this->answers()->where('status', 'completed')->count();
        
        return $totalQuestions > 0 ? round(($completedAnswers / $totalQuestions) * 100) : 0;
    }
}
