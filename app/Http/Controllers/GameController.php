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
        if($request->gameId  && $request->id) {

        }

            //set required variables

            $encryptedID = explode('_', base64_decode($request->id));
            $testPlayer1 = substr($encryptedID[3], 1);;
            $testPlayer2 = auth()->user()->id;
            $data = ['messageData' => [], 'gameData' => []];
            $challengeStatus = null;
            $encryptedID = explode('_', base64_decode($request->gameId));
            $gameId = substr($encryptedID[3], 1);

            $snake = array(0 => 95, 1 => 98, 3 => 87, 4 => 54, 5 => 64 , 6 => 17);
            $snakeByteDiff = [ 20, 20, 63, 20, 4, 10];


            $ladder = array(0 => 4, 1 => 20, 3 => 28, 4 => 40, 5 => 63, 6 => 71);
            $ladderClimDiff = [10, 18, 56, 19, 18, 20];


            //roll the dice
            $diceValue = rand(1,6);

            //retrieve game
            $gameMove = GameMove:: where('game_id', $gameId)->first();
            $originalPlayer1 = $gameMove->player0_id;
            $originalPlayer2 = $gameMove->player1_id;

            //for challenges
            $searchParam1  = $originalPlayer1 . '|' . $originalPlayer2;
            $searchParam2  = $originalPlayer2 . '|' . $originalPlayer1;
            $challenge = Challenge::where('id',$searchParam1)->orWhere('id',$searchParam2)->orderBy('created_at','desc')
            ->first();
            // return ['game' =>$gameId  , 'id'=>$testPlayer1];

            // both players belong to this game
            if(
                ($originalPlayer1 == $testPlayer1 && $originalPlayer2 == $testPlayer2) ||
                ($originalPlayer1 == $testPlayer2 && $originalPlayer2 == $testPlayer1) ||
                $challenge->status !== 'accepted'
            ) {
                // decide which player
                ($testPlayer2 === (int)$originalPlayer1)? $player = 'player0': $player = 'player1';
                $opponentId = substr($player, -1, 1);
                ((int)$opponentId === 0) ? $opponentId = 'player1' : $opponentId = 'player0';
                // Check for valid turn.
                $challengeStatus = $challenge->status;
               // return [$gameMove->whoseTurn,$gameMove->{ $player.'_id' },$gameMove->{ $player.'_rolled' }];

                if($gameMove->whoseTurn !== (int)$gameMove->{ $player.'_id' } || $gameMove->{ $player.'_rolled' } !== 'no' ) { // this checks if the player who have played is a valid player.
                    $gameData = ['diceValue' => $diceValue];
                    $data['gameData'] = $gameData;
                    return array(
                        'status' => true,
                        'message' => 'Not Your Turn',
                        'data' => $data,
                        'challengeStatus' => $challengeStatus
                    );
                } else { // if the user is a valid user to play

                    $oldScore = $gameMove->{ $player.'_score' }; //get old score to generate new score...

                    $newScore = $diceValue + (int)$oldScore; // this will give new score.

                    // test if this is a snake byte or not
                    $snakeIndex = array_search($newScore, $snakeByteDiff);
                    $ladderIndex = array_search($newScore, $ladderClimDiff);
                    if($snakeIndex){
                        $newScore = $newScore - $snakeByteDiff[$snakeIndex];
                    }

                    if($ladderIndex) {
                        $newScore = $newScore + $snakeByteDiff[$snakeIndex];
                    }

                    if($newScore > 99) {  //check if new dice value is making the more than 100;
                        $gameMove->{$player . '_rolled'} = 'yes';
                        $gameMove->{ $opponentId . '_rolled'} = 'no';
                        $gameMove->whoseTurn = $gameMove->{ $opponentId . '_id'};
                        $gameMove->save();
                        $winningDiff = 99 - $oldScore ;
                        $gameData = ['diceValue' => $diceValue];
                        $data['gameData'] = $gameData;
                        return array(
                                'status' => true,
                                'message' => 'You need more '.$winningDiff,
                                'data' => $data,
                                'challengeStatus' => $challengeStatus
                        );
                    } else { // this is an assurance that the move and score now can be saved in the data base.


                            $originalDiff = $gameMove->{$player . '_difference'};
                            if( $gameMove->{$player . '_toPosition'} === '50,550' || $gameMove->{$player . '_toPosition'} === '150,550' ) {
                                // this check if the it is the first move
                                $gameMove->{$player . '_toPosition'} = '0,0';
                            }
                            $newDiff = intdiv($newScore, 10);
                            $toPosition = explode(',', $gameMove->{$player . '_toPosition'});
                            $gameMove->{$player . '_fromPosition'} = $gameMove->{$player . '_toPosition'};
                            if ((int)$originalDiff !== $newDiff ){
                                $diff = $newDiff - (int)$originalDiff;
                                if((($newScore % 10) === 0))
                                {
                                    ($newDiff % 2 == 0)
                                        ?$gameMove->{$player . '_toPosition'} = /*( (int)$toPosition[0] + 1)*/(int)$originalDiff  . ',0'
                                        :$gameMove->{$player . '_toPosition'} = /*( (int)$toPosition[0] + 1)*/(int)$originalDiff  . ',9';
                                    $gameMove->{$player . '_moveType'} = '10changed';
                                }else{

                                    ($diff > 0)?$gameMove->{$player . '_moveType'} = 'increment':$gameMove->{$player . '_moveType'} = 'decrement';

                                    // take care of the end column
                                    ((int)$toPosition[1] === 0 || (int)$toPosition[1] === 9) ? $diceValue = $diceValue -1: $diceValue;

                                    //decide to move left or right
                                    ($newDiff % 2 == 0)
                                        ?$gameMove->{$player . '_toPosition'} = /*( (int)$toPosition[0] + 1)*/$newDiff  . ',' . (($newScore % 10)-1)
                                        :$gameMove->{$player . '_toPosition'} = /*( (int)$toPosition[0] + 1)*/$newDiff  . ',' . (10-($newScore % 10));


                                    $gameMove->{$player . '_difference'} = $newDiff;
                                }
                            } else {

                                // take care of the end column
                                ((int)$toPosition[1] == 0 || (int)$toPosition[1] == 9) ? $diceValue = $diceValue - 1: $diceValue;


                                //decide to move left or right
                                ($newDiff % 2 == 0)
                                    ?$gameMove->{$player . '_toPosition'} = $toPosition[0] . ',' . ((int)$toPosition[1] + $diceValue)
                                    :$gameMove->{$player . '_toPosition'} = $toPosition[0] . ',' . ((int)$toPosition[1] - $diceValue) ;


                                $gameMove->{$player . '_moveType'} = 'increment';
                            }
                            $gameMove->{$player . '_score'} = $newScore;
                            $gameMove->{$player . '_diceValue'} = $diceValue;


                            $gameMove->{$player . '_rolled'} = 'yes';
                            $gameMove->{ $opponentId . '_rolled'} = 'no';
                            $gameMove->whoseTurn = $gameMove->{ $opponentId . '_id'};

                            $gameMove->save();

                            if ((int)$gameMove->{$player . '_score'} === 99) {
                                $challenge->status = 'finished';
                                $challenge->save();
                                $challengeStatus = $challenge->status;
                                $gameData = ['diceValue' => $diceValue];
                                $$data['gameData'] = $gameData;
                                return array(
                                    'status' => true,
                                    'message' => 'You won the game',
                                    'data' => $data,
                                    'challengeStatus' => $challengeStatus
                                );
                            }
                        $gameData = ['diceValue' => $diceValue];
                        $data['gameData'] = $gameData;
                        return array(
                            'status' => true,
                            'message' => 'You won the game',
                            'data' => $data,
                            'challengeStatus' => $challengeStatus
                        );
                    }
                }
            } else{
                {
                    return array(
                        'status' => true,
                        'message' => 'Sorry! This is a invalid request',
                        'data' => $data,
                        'challengeStatus' => null
                    );
                }
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

        //decrypt id
//        if($id !== ''){
            $encryptedID = explode('_', base64_decode($id));
            $id = substr($encryptedID[3], 1);

            $user = auth()->user()->id;
            $data = ['messageData' => [], 'gameData' => []];
            $searchParam1  = $id . '|' . $user;
            $searchParam2  = $user . '|' . $id;
            $challengeStatus = null;
            $challenge = Challenge::where('id',$searchParam1)->orWhere('id',$searchParam2)->orderBy('created_at','desc')
                ->first();

            if($challenge === null || $challenge->status === 'finished') {
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
                $challengeStatus = 'requested';
                return array(
                    'status' => true,
                    'message' => 'Your request has been sent',
                    'data' => $data,
                    'challengeStatus' => $challengeStatus
                );
            }else
                {
                    return array(
                        'status' => true,
                        'message' => 'You cann\'t request',
                        'data' => $data,
                        'challengeStatus' => $challengeStatus
                    );
            }
//        } else{
//            return array('status' => false, 'messages' =>'something went wrong');
//        }
    }

    /**
     * @param $id id of the opponent
     * @return \Illuminate\Http\Response
     */
    public function accept($id){
        $SIZE = 50;
        $HEIGHT = 10;
        $WIDTH = 10;
        if($id !== '') {
            $user = auth()->user()->id;
            $searchParam1 = $id . '|' . $user;
            $searchParam2 = $user . '|' . $id;
            $challenge = Challenge::where('id', $searchParam1)->orWhere('id', $searchParam2)->where('status','requesting')
                ->first();

            if ($challenge !== null && $challenge->game_id === null) {
                $game = new Game();
                $game->player1 = $id;
                $game->player2 = $user;
                $game->who_start = $id;
                $game->board_dimension = $HEIGHT . 'x' . $WIDTH . 'x' . $SIZE;
                $game->save();

                // update challenge table
                $challenge->status = 'accepted';
                $challenge->game_id = $game->id;
                $challenge->save();

                // create new game.
                $move = new GameMove();
                $move->game_id = $game->id;
                $move->whoseTurn = $id;


                // assign new values to player 1
                $move->player0_id = $id;
                $move->player0_pieceId = $id;
                $move->player0_diceValue = 0;
                $move->player0_fromPosition = '0,0';
                $move->player0_toPosition = '50,550';
                $move->player0_moveType = 'initial';
                $move->player0_score = 0;
                $move->player0_difference = 0;
                $move->player0_rolled = 'no';

                // assign new values to player 2
                $move->player1_id = $user;
                $move->player1_pieceId = $user;
                $move->player1_diceValue = 0;
                $move->player1_fromPosition = '0,0';
                $move->player1_toPosition = '150,550';
                $move->player1_moveType = 'initial';
                $move->player1_score = 0;
                $move->player1_difference = 0;
                $move->player1_rolled = 'yes';
                $move->save();

                /********************************************************************************************************************************/

                list($X, $Y) = explode(',', $move->player0_toPosition);
                $player0PositionX = $X;
                $player0PositionY =  $Y;
                $player0Score = $move->player0_score;
                $player0Rolled = $move->player0_rolled;

                list($X, $Y) = explode(',', $move->player1_toPosition);
                $player1PositionX = $X;
                $player1PositionY =  $Y;
                $player1Score = $move->player1_score;
                $player1Rolled = $move->player1_rolled;


                list($HEIGHT, $WIDTH, $SIZE) = explode('x',$challenge->game->board_dimension);

                $gameData = [
                    'game'=> [
                        'id' => base64_encode(rand(0,9) . '_game_' . rand(0,9) . '_' . rand(0,9).$move->game_id . '_'. rand(0,9)),
                        'turn' => $move->whoseTurn,
                        'height' => $HEIGHT,
                        'width' => $WIDTH,
                        'size' => $SIZE
                    ],
                    'player0' => [
                        'id' =>  base64_encode(rand(0,9) . '_player_' . rand(0,9) . '_' . rand(0,9).$move->player0_id . '_'. rand(0,9)),
                        'piece_id' => $move->player0_pieceId,
                        'x' => $player0PositionX,
                        'y' => $player0PositionY,
                        'score' => $player0Score,
                        'rolled' => $player0Rolled
                    ],
                    'player1' => [
                        'id' =>  base64_encode(rand(0,9) . '_player_' . rand(0,9) . '_' . rand(0,9).$move->player1_id . '_'. rand(0,9)),
                        'piece_id' => $move->player1_pieceId,
                        'x' => $player1PositionX,
                        'y' => $player1PositionY,
                        'score' => $player1Score,
                        'rolled' => $player1Rolled
                    ]
                ];
                /********************************************************************************************************************************/

                $data = ['messageData' => [], 'gameData' => $gameData];
                return array('status' => true, 'message' => 'Game started', 'data' => $data, 'challengeStatus' => $challenge->status);
            }
            $data = ['messageData' => [], 'gameData' => []];
            return array('status' => true, 'message' => 'Game already started. Cann\'t make more entries.', 'data' => $data, 'challengeStatus' => $challenge->status);
        }
    }
    /**
     *
     */
    public function getChallenges($id){
        if($id !== ''){
            $user = auth()->user()->id;
            $searchParam1 = $id . '|' . $user;
            $searchParam2 = $user . '|' . $id;
            $challenge = Challenge::where('id', $searchParam1)->orWhere('id', $searchParam2)->where('status','requested')
                ->first();
            $data = ['challenge' => $challenge];
            return array('status'=> true, 'data' => $data);
        }
    }

    public  function checkIfWinner($gameMove){
        return 'Hello World'.$gameMove->player0_diceValue.'something';

    }
    public function getBoardData($id)
    {
        $user = auth()->user()->id;
        $searchParam1 = $id . '|' . $user;
        $searchParam2 = $user . '|' . $id;
        $challenge = Challenge::where('id', $searchParam1)->orWhere('id', $searchParam2)
            ->first();
        $dimension = explode('x', $challenge->game->board_dimension);
      //  return $dimension = $challenge->game->board_dimension;
        $gameData = ['height' => $dimension[0], 'width' => $dimension[1], 'size' => $dimension[2], 'who_start' => $challenge->game->who_start,
            'gameID' => $challenge->game_id, 'positionX' => 0, 'positionY' => 0];
        $data = ['status' => $challenge->status, 'message' => 'Game started', 'gameData' => $gameData];
        return array('status' => true, 'data' => $data);
    }
}
