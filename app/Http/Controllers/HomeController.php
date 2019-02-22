<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;

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

        return View::component('Home', ['employees' => $employees]);
    }
}
