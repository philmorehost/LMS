<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'username', 'email', 'password', 'role', 'avatar', 'bio',
        'phone', 'website', 'linkedin', 'twitter', 'youtube', 'country',
        'status', 'login_notification_enabled', 'social_provider', 'social_id',
        'email_verified_at',
    ];

    protected $hidden = ['password', 'remember_token', 'social_id'];

    protected $casts = [
        'email_verified_at'           => 'datetime',
        'password'                    => 'hashed',
        'login_notification_enabled'  => 'boolean',
    ];

    // ─── Role Helpers ─────────────────────────────────────────────────────────

    public function isAdmin(): bool     { return $this->role === 'admin'; }
    public function isInstructor(): bool { return $this->role === 'instructor'; }
    public function isStudent(): bool   { return $this->role === 'student'; }
    public function hasRole(string|array $role): bool {
        return is_array($role) ? in_array($this->role, $role) : $this->role === $role;
    }

    public function isActive(): bool    { return $this->status === 'active'; }
    public function isSuspended(): bool { return $this->status === 'suspended'; }

    // ─── Avatar ───────────────────────────────────────────────────────────────

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        // Generate initials avatar via UI Avatars
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6366f1&color=fff&size=128';
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function courses()
    {
        return $this->hasMany(\App\Models\Course::class, 'instructor_id');
    }

    public function enrollments()
    {
        return $this->hasMany(\App\Models\Enrollment::class);
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(\App\Models\Course::class, 'enrollments')
            ->withPivot('progress_percent', 'enrolled_at', 'completed_at')
            ->withTimestamps();
    }

    public function wishlist()
    {
        return $this->hasMany(\App\Models\Wishlist::class);
    }

    public function cart()
    {
        return $this->hasMany(\App\Models\Cart::class);
    }

    public function certificates()
    {
        return $this->hasMany(\App\Models\Certificate::class);
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\CourseReview::class);
    }

    public function instructorProfile()
    {
        return $this->hasOne(\App\Models\InstructorProfile::class);
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(\App\Models\Withdrawal::class);
    }

    public function loginAttempts()
    {
        return $this->hasMany(\App\Models\Security\LoginAttempt::class, 'username', 'email');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeAdmins($query)      { return $query->where('role', 'admin'); }
    public function scopeInstructors($query) { return $query->where('role', 'instructor'); }
    public function scopeStudents($query)    { return $query->where('role', 'student'); }
    public function scopeActive($query)      { return $query->where('status', 'active'); }
}
