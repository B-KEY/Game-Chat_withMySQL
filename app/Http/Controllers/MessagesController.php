<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;

class MessagesController extends Controller
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
        $message = new Message();
        $id = $request->id;
        $message->id = auth()->user()->id . '|' . $request->id;
        $message->sender = auth()->user()->id;
        $message->body = $request->message;
        $message->available = true;
        $message->receiver = $request->id;
        $message->save();
        $userid = auth()->user()->id . '|' . $id;
        $userid2 = $id. '|' . auth()->user()->id;
        $messages = Message::where('id',$userid)->orWhere('id',$userid2)->orderBy('created_at','desc')->take(1)->get();
        return $messages;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userid = auth()->user()->id . '|' . $id;
        $userid2 = $id. '|' . auth()->user()->id;
        $mess = new Message();
        $mal = $mess->where('id',$userid)->orWhere('id',$userid2)->get();
        return $mal;
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getMore($id, $date)
    {

        $userid = auth()->user()->id . '|' . $id;
        $userid2 = $id. '|' . auth()->user()->id;
        $msg = new Message();
        $messages = Message::where([
            ['id', '=', $userid],
            ['created_at', '>', $date]
        ])->orWhere([
            ['id', '=', $userid2],
            ['created_at', '>', $date]
        ])->orderBy('created_at','desc')->take(1)->get();
        ;
        //return $date;
        //$messages = Message::where('created_at', '>', $date)->whereIn('id', $userid)return $messages;
        return $messages;
    }
}
