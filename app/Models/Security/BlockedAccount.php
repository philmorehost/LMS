<?php
namespace App\Models\Security;
use Illuminate\Database\Eloquent\Model;
class BlockedAccount extends Model {
    protected $table = 'blocked_accounts';
    protected $fillable = ['username','email','blocked_by','reason','blocked_at','blocked_until','is_active'];
    protected $casts = ['is_active' => 'boolean', 'blocked_at' => 'datetime', 'blocked_until' => 'datetime'];
}
