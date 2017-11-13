<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Challenge;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param $id id of the opponent
     * @return \Illuminate\Http\Response
     */
    public function invite($id){

        //return array('status' => true, 'data' => ['message' =>'Your request has been sent']);

        if($id !== ''){
            $challenge = new Challenge();
            $receiver_id = $id;
            $sender_id = auth()->user()->id;
            $message_id = $sender_id . '|' . $receiver_id;
            $challenge->id = $message_id;
            $challenge->sender = $sender_id;
            $challenge->active = true;
            $challenge->status = 'requested';
            $challenge->receiver = $receiver_id;
            $challenge->save();
            $data = ['message' => 'Your request has been sent'];
            return array('status' => true, 'data' => $data);
        }
        else{
            return array('status' => false, 'messages' =>'something went wrong');
        }
    }

    /**
     * @param $id id of the opponent
     * @return \Illuminate\Http\Response
     */
    public function accept($id){
        if($id !== ''){
            $user = auth()->user()->id;
            $challenge_id = $id . '|' . $user;
            $challenge = Challenge:: find($challenge_id);
            $challenge->status = 'accepted';
            $challenge->save();
            $data = ['status' => $challenge->status, 'message' => 'Game started' ];

            return array('status'=> true, 'data' => $data);
        }
    }
}
