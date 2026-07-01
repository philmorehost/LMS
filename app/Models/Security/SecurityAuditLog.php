<?php
namespace App\Models\Security;
use Illuminate\Database\Eloquent\Model;
class SecurityAuditLog extends Model {
    public $timestamps = false;
    protected $table = 'security_audit_log';
    protected $fillable = ['event_type','ip_address','username','country_code','country_name','action_taken','details','severity','occurred_at'];
    protected $casts = ['occurred_at' => 'datetime'];
}
