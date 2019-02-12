<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Display the user home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = auth()->user()->employees()->with('company')->get();

        return view('home.index')
            ->withEmployees($employees);
    }
}
