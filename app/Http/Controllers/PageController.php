<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function main()
    {
        return view('main');
    }
    public function about()
    {
        return view('about');
    }
    public function services()
    {
        return view('services');
    }
    public function servicesDetail()
    {
        return view('servicesDetail');
    }
    public function contact()
    {
        return view('contact');
    }

}
