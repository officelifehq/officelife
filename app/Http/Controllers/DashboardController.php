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
        $teams = auth()->user()->account->teams()->with('users')->get();

        return view('dashboard.index')
            ->withTeams($teams)
            ->withNumberTeams($teams->count())
            ->withNumberEmployees(auth()->user()->account->users()->count());
    }
}
