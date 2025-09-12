<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    protected $fillable = [
        'interview_id',
        'question',
        'order',
        'time_limit',
    ];

    /**
     * Get the interview this question belongs to
     */
    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }
}
