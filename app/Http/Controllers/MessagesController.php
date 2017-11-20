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
        $type = $request->type;

        if(($type === 'group') ||($type=='individual')) {
            $message = new Message();
            $reciever_id = $request->id;
            $sender_id = auth()->user()->id;
            $message_id = $sender_id . '|' . $reciever_id;

            ($type === 'group')? $message->id = $reciever_id: $message->id = $message_id;
            $message->sender = $sender_id;
            $message->body = $request->message;
            $message->available = true;
            $message->receiver = $reciever_id;
            $message->save();

            //retrieve the first row.
            $search_param1 = $message_id;
            $search_param2 = $reciever_id. '|' . auth()->user()->id;

            ($request->type !== 'group') ? $messages  = Message::where('id',$search_param1)
                    ->orWhere('id',$search_param2)->orderBy('created_at','desc')
                    ->take(1)->get()
                :
                $messages = Message::where('id',$reciever_id)->orderBy('created_at','desc')
                    ->take(1)->get();
            $data = [];
            foreach($messages as $msg) {
                $data[] = ['body' => $msg->body, 'created_at' => $msg->created_at->format('H:i'),
                    'username' => $msg->user->name, 'userimage' => $msg->user->image_url];
            }
            return array('status' => true, 'data' => $data);
        }
        else{
            return array('status' => false, 'messages' =>'something went wrong');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)

    {

        // apply encryption
        $type = $request->type;

        if(($type === 'group') || ($type=='individual')) {
            $user_id1 = $id;
            $user_id2 = auth()->user()->id;

            $search_param1 = $user_id1 . '|' . $user_id2;
            $search_param2 = $user_id2. '|' . $user_id1;

            ($type!== 'group') ? $messages  = Message::where('id',$search_param1)->orWhere('id',$search_param2)
                ->orderBy('created_at','desc')->get()
                : $messages = Message::where('id',$id)->orderBy('created_at','desc')->get();
            $data = [];
            foreach($messages as $msg){
                $data[] = ['body' => $msg->body , 'created_at' => $msg->created_at->format('H:i'),
                                'username' => $msg->user->name, 'userimage' => $msg->user->image_url];
            }

            return array('status' => true, 'data' => $data);
        }
        else{
            return array('status' => false, 'messages' =>'something went wrong');
        }


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
        //return $date;
        //$messages = Message::where('created_at', '>', $date)->whereIn('id', $userid)return $messages;
        return $messages;
    }


}
