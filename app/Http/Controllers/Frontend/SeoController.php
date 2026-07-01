<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function __call($method, $args) { return response()->json(["status" => "stub", "method" => $method, "class" => __CLASS__]); }
}
