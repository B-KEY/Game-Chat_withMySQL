<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\User;
use App\Group;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        $groups = Group::all();
        return view('dashboard')->with('users', $users)->with('groups', $groups);
    }
}
