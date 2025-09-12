<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user has admin role
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user has reviewer role
     */
    public function isReviewer(): bool
    {
        return $this->role === 'reviewer';
    }

    /**
     * Check if user has candidate role
     */
    public function isCandidate(): bool
    {
        return $this->role === 'candidate';
    }

    /**
     * Check if user can manage interviews (admin or reviewer)
     */
    public function canManageInterviews(): bool
    {
        return in_array($this->role, ['admin', 'reviewer']);
    }

    /**
     * Get interviews created by this user
     */
    public function createdInterviews()
    {
        return $this->hasMany(Interview::class, 'created_by');
    }

    /**
     * Get submissions by this user
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
