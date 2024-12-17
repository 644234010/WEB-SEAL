<?php

namespace App\Http\Controllers;

use Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class sidebarcontroller extends Controller
{

    public function about()
    {
        return view('about');
    }
    public function allpd()
    {
        return view('allpd');
    }
    public function Sales_summary()
    {
        return view('Sales_summary');
    }
    public function getUserType()
    {
        return Auth::user()->type;
    }
}

