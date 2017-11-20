<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\User;
use App\Group;
use App\Challenge;

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
        $user_id = auth()->user()->id;
        $challenge = Challenge::where('receiver', $user_id)->get();
        $challenges = [];
        foreach($challenge as $c) {
            $challenges[] = ['challenger_id' => $c->sender, 'challenger' => $c->haveChallenged->name];
        }

        $users = User::where('id','!=',$user_id)->get();
        $groups = Group::all();
        return view('dashboard')->with('users', $users)->with('groups', $groups)->with('challenges',$challenges);

    }
}
