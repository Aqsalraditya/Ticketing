<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    protected $fillable = [
        'email',
        'ip_address',
        'successful',
        'type',
    ];

    protected $casts = [
        'successful' => 'boolean',
    ];

    public function scopeSecure($query)
    {
        return $query->where('type', 'secure');
    }

    public function scopeVulnerable($query)
    {
        return $query->where('type', 'vulnerable');
    }

    public function scopeFailed($query)
    {
        return $query->where('successful', false);
    }

    public static function countRecentAttempts($email, $type, $minutes = 1)
    {
        return static::where('email', $email)
            ->where('type', $type)
            ->where('successful', false)
            ->where('created_at', '>=', now()->subMinutes($minutes))
            ->count();
    }
}