<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.index');
    }
}
