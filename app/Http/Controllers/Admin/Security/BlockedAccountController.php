<?php

namespace App\Http\Controllers\Admin\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlockedAccountController extends Controller
{
    public function __call($method, $args) { return response()->json(["status" => "stub", "method" => $method, "class" => __CLASS__]); }
}
