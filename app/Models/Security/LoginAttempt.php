<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    public $timestamps = false;
    protected $table = 'login_attempts';
    protected $fillable = [
        'identifier_type', 'identifier', 'ip_address', 'username',
        'user_agent', 'country_code', 'country_name', 'success',
        'failure_reason', 'attempted_at',
    ];
    protected $casts = ['success' => 'boolean', 'attempted_at' => 'datetime'];
}
