<?php

namespace App\Http\Controllers;

class BackController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $app_url = env('APP_URL');
        return view('backoffice', compact('app_url'));
    }
}
