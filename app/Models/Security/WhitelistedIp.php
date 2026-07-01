<?php
namespace App\Models\Security;
use Illuminate\Database\Eloquent\Model;
class WhitelistedIp extends Model {
    protected $table = 'whitelisted_ips';
    protected $fillable = ['ip_address','cidr_range','label','recognized_auto','king_status','times_seen','successful_sessions','added_by','added_at'];
    protected $casts = ['recognized_auto' => 'boolean', 'king_status' => 'boolean', 'added_at' => 'datetime'];
}
