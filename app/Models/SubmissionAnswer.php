<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class SubmissionAnswer extends Model
{
    protected $fillable = [
        'submission_id',
        'question_id',
        'video_path',
        'recording_duration',
        'attempts',
        'started_at',
        'completed_at',
        'status',
        'metadata',
        'score',
        'rating',
        'comments',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function hasVideo(): bool
    {
        return ! empty($this->video_path)
            && Storage::disk('public')->exists($this->video_path);
    }

    public function getVideoUrl(): ?string
    {
        if (! $this->hasVideo()) {
            return null;
        }

        // Public disk is linked to /storage via storage:link
        return asset('storage/'.ltrim($this->video_path, '/'));
    }

    public function getVideoSize(): ?int
    {
        return $this->hasVideo()
            ? Storage::disk('public')->size($this->video_path)
            : null;
    }

    public function getVideoMimeType(): string
    {
        $path = strtolower((string) $this->video_path);
        if (str_ends_with($path, '.mp4')) {
            return 'video/mp4';
        }
        if (str_ends_with($path, '.webm')) {
            return 'video/webm';
        }

        return 'video/webm';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isRecording(): bool
    {
        return $this->status === 'recording';
    }

    public function getFormattedDuration(): string
    {
        if (! $this->recording_duration) {
            return '00:00';
        }

        $minutes = floor($this->recording_duration / 60);
        $seconds = $this->recording_duration % 60;

        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
