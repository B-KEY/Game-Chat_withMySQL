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
        if($request->gameID!= '')
        {
            $gameID = $request->gameID;

            $gameMove = GameMove:: where('game_id', $gameID)->first();

            $diceValue = rand(1,6);

            $oldScore = $gameMove->player0_score;
            // check if already won
            $newScore = $diceValue + (int)$oldScore;
            if($newScore > 99)
            {
                $winningDiff = 99 - $oldScore ;
                if ($winningDiff !== $diceValue)
                    return array('diceValue'=>$gameMove->player0_diceValue, 'message'=>'You need more '.$winningDiff, 'status'=>true);
            }

            $originalDiff = $gameMove->player0_difference;

            $newDiff = intdiv($newScore, 10);
            $toPosition = explode(',', $gameMove->player0_toPosition);

            if((int)$originalDiff !== $newDiff)
            {
                    $diff = $newDiff - (int)$originalDiff;
                    if($diff > 0)// case increment
                    {
                        $gameMove->player0_fromPosition = $gameMove->player0_toPosition;
                        $gameMove->player0_toPosition = ((int)$toPosition[0]+1).','. ($newScore%10);
                        $gameMove->player0_difference = $newDiff;
                        $gameMove->player0_moveType='increment';
                    } else{
                        $gameMove->player0_fromPosition = $gameMove->player0_toPosition;
                        $gameMove->player0_toPosition = ((int)$toPosition[0]-1).','. ($newScore%10);
                        $gameMove->player0_difference = $newDiff;
                        $gameMove->player0_moveType='decrement';
                    }
            } else{
                $gameMove->player0_fromPosition = $gameMove->player0_toPosition;
                $gameMove->player0_toPosition = $toPosition[0].','. ((int)$toPosition[1]+$diceValue);
                $gameMove->player0_moveType='increment';
                $gameMove->save();
            }

            $gameMove->player0_score = $newScore;
            $gameMove->player0_diceValue = $diceValue;
            $gameMove->save();
            GameController::checkIfWinner($gameMove);
            if((int)$gameMove->player0_score === 99) {return array('status'=> true, 'message'=>'You won the game',
                'diceValue'=>$gameMove->player0_diceValue,
                'data'=>$gameMove);}

            return array('diceValue'=>$gameMove->player0_diceValue);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


        $gameMove = GameMove:: where('game_id', $id)->first();
        $toPosition = explode(',', $gameMove->player0_toPosition);
        return array('X'=> $toPosition[0], 'Y'=> $toPosition[1]);

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
        $SIZE = 50;
        $HEIGHT = 10;
        $WIDTH = 10;
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
            $game->board_dimension = $HEIGHT.'x'.$WIDTH.'x'.$SIZE;
            $game->save();
            // $data = ['status' => $challenge->status, 'message' => 'Game started',  'gameData' =>  $gameData ];

            //Moves detail for the user who challenged
            $p1 = 'player1';
            $p2 = 'player2';

            $move  = new GameMove();
            $move->game_id = $game->id;
            $move->whoseTurn = $id;

            $move->player0_id =$id;
            $move->player0_pieceId=$id;
            $move->player0_diceValue=0;
            $move->player0_fromPosition='0,0';
            $move->player0_toPosition='0,0';
            $move->player0_moveType='initial';
            $move->player0_score=0;
            $move->player0_difference=0;

            $move->player1_id=$user;
            $move->player1_pieceId=$user;
            $move->player1_diceValue=0;
            $move->player1_fromPosition='0,0';
            $move->player1_toPosition='0,0';
            $move->player1_moveType='initial';
            $move->player1_score=0;
            $move->player1_difference=0;
            $move->save();
            $toPosition = explode(',', $move->player0_toPosition);
            $gameData = ['height' => $HEIGHT, 'width' => $WIDTH, 'size' => $SIZE, 'who_start' => $game->who_start, 'gameID' => $game->id,
                'positionX'=> $toPosition[0], 'positionY' => $toPosition[1]];

            $data = ['status' => $challenge->status, 'message' => 'Game started',  'gameData' =>  $gameData ];
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

    public  function checkIfWinner($gameMove){
        return 'Hello World'.$gameMove->player0_diceValue.'something';


    }
}
