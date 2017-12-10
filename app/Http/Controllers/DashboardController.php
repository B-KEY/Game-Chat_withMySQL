<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\User;
use App\Group;
use App\Challenge;
use App\Message;
use App\GameMove;

class DashboardController extends Controller
{

    private $error = 'Something went wrong';
    private $success = 'Retrieval was successful';


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
        $challenges = [];
        $groupArray = [];
        $userArray =[];
        $error = "Something went wrong";

        try {
            /**************************************************************************************************************************************/
            // getting all the challenges and preparing array

            $challenge = Challenge::where('receiver', $user_id)->where('status','requested')->get();

            foreach($challenge as $c) {
                $challenges[] = ['challenger_id' => $c->sender, 'challenger' => $c->haveChallenged->name];
            }
            /**************************************************************************************************************************************/

            /**************************************************************************************************************************************/
            // getting all the users and preparing array

            $users = User::where('id','!=',$user_id)->get();

            foreach($users as $user) {
                $userArray[] = ['id' => /*base64_encode(rand(0,9) . '_player_' . rand(0,9) . '_' . rand(0,9).*/$user->id/* . '_'. rand(0,9))*/,
                    'image_url' => $user->image_url, 'name' => $user->name];
            }
            /**************************************************************************************************************************************/

            /**************************************************************************************************************************************/
            // getting all the groups and preparing array

            $groups = Group::all();
            foreach($groups as $group) {
                $groupArray[] = ['id' => /*base64_encode(rand(0,9) . '_group_' . rand(0,9) . '_' . rand(0,9).*/$group->id/* . '_'. rand(0,9))*/,
                    'image_url' => $group->image_url, 'name' => $group->name];
            }
            /**************************************************************************************************************************************/

            return view('dashboard')->with('users', $userArray)->with('groups', $groupArray)->with('challenges',$challenges)->with('message','You aer ready to Go!');;

        } catch (Exception $ex) {
            return view('dashboard')->with('users', $userArray)->with('groups', $groupArray)->with('challenges',$challenges)->with('message','Something went wrong');
        }
    }
    // End of index function.


    public function show($type,$id)
    {
        // apply encryption
        try {
            $challengeStatus = null;
            $messageData = [];
            $data = [];
            if (($type === 'group') || ($type == 'individual')) {
                /*$encryptedID = explode('_', base64_decode($id));
                $id = substr($encryptedID[3], 1);*/
                //return $id;

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
                        'username' => $msg->user->name,
                        'userimage' => $msg->user->image_url
                    ];
                }
                /**************************************************************************************************************************************/


                /**************************************************************************************************************************************/
                // Check if the selected user is a individual user. If yes check if this user is already playing/challenged selected user.
                if ($type === 'individual') {
                    $challenge = Challenge::where('id', $searchParam1)->orWhere('id', $searchParam2)->where('status','!=','finished')
                        ->first();
                    $gameData = [];
                    if ($challenge) {
                        $challengeStatus = $challenge->status;
                        if($challengeStatus === 'accepted'){
                            $gameMove = GameMove:: where('game_id', $challenge->game_id)->first();

                            list($X, $Y) = explode(',',$gameMove->player0_toPosition);
                            $player0PositionX = $X;
                            $player0PositionY =  $Y;
                            $player0Score = $gameMove->player0_score;
                            $player0Rolled = $gameMove->player0_rolled;
                            $player0Name = $challenge->haveChallenged->name;

                            list($X, $Y) = explode(',',$gameMove->player1_toPosition);
                            $player1PositionX = $X;
                            $player1PositionY =  $Y;
                            $player1Score = $gameMove->player1_score;
                            $player1Rolled = $gameMove->player1_rolled;
                            $player1Name = $challenge->getChallenged->name;

                            list($HEIGHT, $WIDTH, $SIZE) = explode('x',$challenge->game->board_dimension);

                            $gameData = [
                                'game'=> [
                                            'id' => /*base64_encode(rand(0,9) . '_game_' . rand(0,9) . '_' . rand(0,9).*/$gameMove->game_id/* . '_'. rand(0,9))*/,
                                            'turn' => $gameMove->whoseTurn,
                                            'height' => $HEIGHT,
                                            'width' => $WIDTH,
                                            'size' => $SIZE
                                ],
                                'player0' => [
                                            'id' =>  /*base64_encode(rand(0,9) . '_player_' . rand(0,9) . '_' . rand(0,9).*/$gameMove->player0_id/* . '_'. rand(0,9))*/,
                                            'piece_id' => $gameMove->player0_pieceId,
                                            'x' => $player0PositionX,
                                            'y' => $player0PositionY,
                                            'score' => $player0Score,
                                            'rolled' => $player0Rolled,
                                            'name' => $player0Name
                                ],
                                'player1' => [
                                            'id' =>  /*base64_encode(rand(0,9) . '_player_' . rand(0,9) . '_' . rand(0,9).*/$gameMove->player1_id/* . '_'. rand(0,9))*/,
                                            'piece_id' => $gameMove->player1_pieceId,
                                            'x' => $player1PositionX,
                                            'y' => $player1PositionY,
                                            'score' => $player1Score,
                                            'rolled' => $player1Rolled,
                                            'name' => $player1Name
                                ]
                            ];
                        }
                    }
                    $data = ['messageData' => $messageData, 'gameData' => $gameData];
                    return $this -> sendResponse(true, $this->success, $data, $challengeStatus);
                }
                /**************************************************************************************************************************************/

                //return data for group users
                return $this -> sendResponse(true, $this->success, $data, $challengeStatus);

            } else {
                return $this -> sendResponse(false, $this->error, null, $challengeStatus);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            return $this -> sendResponse(false, $this->error, null, $challengeStatus);
        } catch(Exception $ex) {
            return $this -> sendResponse(false, $this->error, null, $challengeStatus);
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

    public function returnChallenge($player0, $player2){
        //for challenges
        $searchParam1  = $player0 . '|' . $player2;
        $searchParam2  = $player2 . '|' . $player0;

        return  Challenge::where('id',$searchParam1)
            ->orWhere('id',$searchParam2)
            ->orderBy('created_at','desc')
            ->first();
    }

}
