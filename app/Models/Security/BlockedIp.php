<?php
namespace App\Models\Security;
use Illuminate\Database\Eloquent\Model;
class BlockedIp extends Model {
    protected $table = 'blocked_ips';
    protected $fillable = ['ip_address','cidr_range','country_code','country_name','blocked_by','block_type','reason','blocked_at','blocked_until','at_firewall_level','is_active'];
    protected $casts = ['is_active' => 'boolean', 'at_firewall_level' => 'boolean', 'blocked_at' => 'datetime', 'blocked_until' => 'datetime'];
}
