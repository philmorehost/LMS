<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function __call($method, $args) { return response()->json(["status" => "stub", "method" => $method, "class" => __CLASS__]); }
}
