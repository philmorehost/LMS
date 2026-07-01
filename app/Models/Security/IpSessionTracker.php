<?php
namespace App\Models\Security;
use Illuminate\Database\Eloquent\Model;
class IpSessionTracker extends Model {
    public $timestamps = false;
    protected $table = 'ip_session_tracker';
    protected $fillable = ['ip_address','user_id','session_id','success','logged_at'];
    protected $casts = ['success' => 'boolean', 'logged_at' => 'datetime'];
}
