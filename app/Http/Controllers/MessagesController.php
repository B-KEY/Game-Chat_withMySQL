<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\Challenge;

class MessagesController extends Controller
{
    private $sender; // always auth()->user->id;
    private $receiver; // opponent
    private $data ; // data to send
    private $type; // type of message
    private $challengeStatus;
    private $message = [
        'newMessage' => 'New message retrieved successfully',
        'allMessage' => 'All message retrieved successfully',
        'badRequest' => 'Bad Request'
    ];
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

        if($this -> checkRequest($request, '')) {

            $message = new Message();
            $message_id = $this -> sender . '|' . $this -> receiver;

            ($this -> type === 'group')? $message->id = $this -> receiver: $message->id = $message_id;

            $message -> sender = $this -> sender;
            $messageBody = filter_var ( $request->message, FILTER_SANITIZE_STRING);
            $message -> body = $messageBody;
            $message -> available = true;
            $message -> receiver = $this -> receiver;
            $message -> save();


            //retrieve the first row.
            $search_param1 = $message_id;
            $search_param2 = $this -> receiver. '|' .$this -> sender;

            ($request->type === 'individual')
                ? $message = Message::where ('id', $search_param1)
                    -> orWhere('id', $search_param2)
                    -> orderBy('created_at', 'desc')->first()
                : $message = Message::where('id', $this -> receiver)
                    ->orderBy('created_at', 'desc')
                    ->first();

            $this -> data['messageData'] = [
                ['body' => $message->body,
                'created_at' => $message->created_at->format('H:i'),
                'userName' => $message->user->name,
                'userImage' => $message->user->image_url]
            ];

            return $this->sendResponse(true, $this -> message['newMessage'], $this -> data, $this -> challengeStatus);
        }
        else{
            return $this->sendResponse(true, $this -> message['badRequest'], $this -> data, $this -> challengeStatus);
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
//        if($this -> checkRequest($request, $id)) {
//            $challengeData = [];
//
//            $searchParam1 = $user_id1 . '|' . $user_id2;
//            $searchParam2 = $user_id2 . '|' . $user_id1;
//
//            ($type !== 'group') ? $messages = Message::where('id', $searchParam1)->orWhere('id', $searchParam2)
//                ->get()
//                : $messages = Message::where('id', $id)->get();
//            $data = [];
//            foreach ($messages as $msg) {
//                $data[] = ['body' => $msg->body, 'created_at' => $msg->created_at->format('H:i'),
//                    'username' => $msg->user->name, 'userimage' => $msg->user->image_url];
//            }
//            if ($type === 'individual') {
//                $challenge = Challenge::where('id', $searchParam1)->orWhere('id', $searchParam2)
//                    ->first();
//                if ($challenge) {
//                    $challengeData = ['challengeStatus' => $challenge->status];
//                }
//            }
//
//            return array('status' => true, 'data' => $data, 'challengeData'=>$challengeData);
//        }
//        else{
//            return array('status' => false, 'messages' =>'something went wrong');
//        }
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
    public function getMore($id, $date, $type)
    {
        $data = ['messageData' => [], 'gameData' => []];

        $this -> receiver = filter_var ( $id, FILTER_SANITIZE_STRING);
        $date = filter_var ( $date, FILTER_SANITIZE_STRING);
        $type = filter_var ( $type, FILTER_SANITIZE_STRING);
        if(!$type || !$this->receiver || !$date){
            return $this->sendResponse(true, $this->message['badRequest'], $data, null);
        }

        $user_id1 = $id;
        $user_id2 = auth()->user()->id;

        $searchParam1 = $user_id1 . '|' . $user_id2;
        $searchParam2 = $user_id2 . '|' . $user_id1;


        /**************************************************************************************************************************************/
        // getting all the messages any way ( be it be individual user or group)

        ($type !== 'group') ? $messages = Message::where('id', $searchParam1)->orWhere('id', $searchParam2)
            ->get()
            : $messages = Message::where('id', $id)->get();
        foreach ($messages as $msg) {
            $messageData[] = [
                'body' => $msg->body,
                'created_at' => $msg->created_at->format('H:i'),
                'userName' => $msg->user->name,
                'userImage' => $msg->user->image_url
            ];
        }

        $data['messageData'] = $messageData;
        return $this->sendResponse(true, $this -> message['newMessage'], $data, null);

    }


    public function returnChallenge($player0, $player2, $condition){
        //for challenges
        $searchParam1  = $player0 . '|' . $player2;
        $searchParam2  = $player2 . '|' . $player0;
        switch($condition){
            case 'neutral':
                return  Challenge::where('id',$searchParam1)
                    -> orWhere('id',$searchParam2)
                    -> orderBy('created_at','desc')
                    -> first();
            case 'requested':
                return  Challenge::where('id', $searchParam1)
                    -> orWhere('id', $searchParam2)
                    -> where('status','requested')
                    -> orderBy('created_at','desc')
                    -> first();
        }

    }

    public function sendResponse($status, $message, $data, $challengeStatus){

        return array(
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'challengeStatus' => $challengeStatus
        );

    }

    public function checkRequest($request, $id) {
        $this -> type = $request->type;
        ($request->id)
            ? $this -> receiver = filter_var ( $request->id, FILTER_SANITIZE_STRING)
            : $this -> receiver = filter_var ( $id, FILTER_SANITIZE_STRING);
        if((( $this -> type === 'group' ) || ( $this -> type == 'individual' )) && $this -> receiver !== '') {
            $this -> data = ['messageData' => [], 'gameData' => []];
            $this -> sender = auth() -> user() -> id;
            $this -> challengeStatus = null;
            return true;
        }
        return false;
    }

}
