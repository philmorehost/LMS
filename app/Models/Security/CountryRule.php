<?php
namespace App\Models\Security;
use Illuminate\Database\Eloquent\Model;
class CountryRule extends Model {
    protected $table = 'country_rules';
    protected $fillable = ['country_code','country_name','flag_emoji','action'];
}
