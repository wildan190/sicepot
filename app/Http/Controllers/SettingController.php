<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the application settings page.
     */
    public function index()
    {
        return view('settings.index');
    }
}
