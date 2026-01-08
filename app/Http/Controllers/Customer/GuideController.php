<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

class GuideController extends Controller
{
    public function index()
    {
        return view('customer.guide');
    }
}
