<?php

namespace App\Http\Controllers\Dashboard\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class HomeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth'
        ];
    }

    public function index(){
        return view('dashboard.site.home.index');
    }
}
