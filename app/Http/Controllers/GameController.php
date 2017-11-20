<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Challenge;
use App\Game;
use App\GameMove;
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



        // check if the game_id exist
        if($request->id != '')
        {
            $gamemove = GameMove:: where('game_id','3')->orderBy('created_at','desc')->first();


            $dice_value = rand(1,6);

            $oldPosition = $gamemove->to_position;
            $newPosition = (int)$gamemove->to_positon + $dice_value;

            $playermove  = new GameMove();
            $playermove->game_id = 3;
            $playermove->player_id = auth()->user()->id;
            $playermove->piece_id = auth()->user()->id;
            $playermove->dice_value = $dice_value;
            $playermove->from_position = $oldPosition;
            $playermove->to_position = $newPosition;
            $playermove->move_type = 'increment';
            $playermove->save();

        }
        return array('dice_value'=>$dice_value);
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

            $user = auth()->user()->id;
            $challenge_id  = $id . '|' . $user;
            $challenge = Challenge:: find($challenge_id);

            if($challenge == null) {
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
            }else {
                return array('status' => false, 'messages' => 'something went wrong');
            }
        } else{
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
            $game = new Game();
            $game->player1 = $id;
            $game->player2 = $user;
            $game->who_start = $id;
            $game->board_dimension = '10x10x50';
            $game->save();
            $game_data = ['board_dimension' => $game->board_dimension, 'who_start' => $game->who_start];
            $data = ['status' => $challenge->status, 'message' => 'Game started',  'game_data' =>  $game_data ];

            //Moves detail for the user who challenged
            $player1move  = new GameMove();
            $player1move->game_id = 3;
            $player1move->player_id = $id;
            $player1move->piece_id = $id;
            $player1move->dice_value = 0;
            $player1move->from_position = 0;
            $player1move->to_position = 0;
            $player1move->move_type = 'nothing';
            $player1move->save();

            //Moves detail for the user who accepted
            $player2move  = new GameMove();
            $player2move->game_id = 3;
            $player2move->player_id = $user;
            $player2move->piece_id = $user;
            $player2move->dice_value = 0;
            $player2move->from_position = 0;
            $player2move->to_position = 0;
            $player2move->move_type = 'nothing';
            $player2move->save();

            return array('status'=> true, 'data' => $data);
        }
    }

    /**
     *
     */
    public function getChallenges($id){
        if($id !== ''){
            $user = auth()->user()->id;
            $challenge_id  = $id . '|' . $user;
            $challenge = Challenge:: find($challenge_id);

            $data = ['challenge' => $challenge];
            return array('status'=> true, 'data' => $data);
        }
    }
}
