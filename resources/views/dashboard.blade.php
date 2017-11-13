@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <div id="stat">
                    <div id="gameplayed" class="statbox">
                        <span>Game Played: </span><span class="number"> 3</span>
                    </div>
                    <div id="rating" class="statbox">
                        <span>Rating: </span><span class="number"> 3.0</span>
                    </div>
                    <div id="gameloss" class="statbox">
                        <span>Total Loss: </span><span class="number"> 3</span>
                    </div>
                    <div id="gamewon" class="statbox">
                        <span>Total Won: </span><span class="number"> 5</span>
                    </div>
                    <div id="gamedrawn" class="statbox">
                        <span>Game Drawn: </span><span class="number"> 0</span>
                    </div>
                </div>
                <div id="game" class=" invisible">
                <div class="tab">
                    <span id="userName"></span>
                    <button class="tablinks" onclick="openCity(event, 'London')">Chat</button>
                    <button class="tablinks" onclick="openCity(event, 'Paris')">Game</button>
                </div>

                <div id="London" class="tabcontent">
                    <div id="message" style="height:100%;" >
                        <div>
                            <div style="padding:5px;width:100%;height:535px;overflow:hidden;" class="chatBox">
                                <div>
                                    <div class="chatMessage" style="height:430px; overflow-x:hidden; overflow-y:auto;">

                                    </div>
                                    <div style="width:100%;color:#000;text-align:right">
                                        <input style="border:none;width:100%;height: 50px;margin-top:10px;margin-bottom: 5px; padding:10px
                                                ;background:rgba(141,163,153,.4)border-radius: 5px;"
                                               type="text" name="message" class="message"  placeholder="Type your message" >
                                        <button onclick="send(this)" style="border:1px solid #080808; -webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;background-color: #337ab7">Send</button>
                                        <input id="receiver_id" type="hidden" >
                                        <input id="lst_saved" type = "hidden">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="Paris" class="tabcontent">

                        <div id="gameBox">
                            <div id="challenge">
                                <textarea placeholder="Enter text you send(optional)"></textarea>
                                <img src="images/challenge.png"  style="height:50px;width:50px;cursor:pointer" onclick="invite(this)"/>
                            </div>

                            <div id="invite_message" class="invisible">
                                <h3>Your request has been sent.</h3>
                                <span>Game will begin once the user accept the request</span>
                            </div>
                            <input id="opponent_id" type="hidden" >
                        </div>
                    <div id="playground" class="invisible">
                        <div>
                            <svg id="gameBoard" width="500" height="500" style="border:2px solid #000;">
                                Sorry! Your browser doesn't support SVG.
                            </svg>
                            <input type="number" min="1" max="6"> <input type="button" value="Roll">
                            <div id="dice"></div>

                        </div>
                    </div>
                </div>
                </div>

            </div>
            <div class="col-md-3" style="background:#4C516D;color:white;height:600px;">

                <div style="text-align:right">@if(count($challenges)>0)<span style="font-size:18px">{{count($challenges)}}</span> @endif
                    <i class="fa fa-bell" style="font-size:20px;margin-top:5px;" aria-hidden="true" onclick="showChallenges(this)"></i>
                    </div>
                @if(count($challenges)>0)

                <div id="showChallengers" class="invisible" style="background:rgba(255,255,255,1);color:#000;z-index:100;
                border:0.5px solid #444;border-radius:10px;opacity:.4">
                <ul>
                @foreach($challenges as $n)

                    <li style="list-style:none;width:300px;cursor:pointer" id="{{ $n['challenger_id'] }}" onclick="acceptChallenge(this)" >
                        {{ $n['challenger'] }} <i class="fa fa-times" aria-hidden="true"></i></li>
                @endforeach
                    </ul>
                 </div>
                @endif

                Users
                @if(count($users)>0)
                    <ul style=" #FFFFFF;">
                        @foreach($users as $user)
                            <li style="list-style:none;width:300px;cursor:pointer">
                                <h5 id="{{$user->id}}" onclick="popupChat(this)" title="individual">
                                    <img src="{{$user->image_url}}"  style="height:30px;width:30px"/> {{$user->name}}
                                </h5>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <hr>
                    ChatRooms/ Groups
                    @if(count($groups)>0)
                        <ul style="#FFFFFF;">
                            @foreach($groups as $group)
                                <li style="list-style:none;width:300px;cursor:pointer">
                                    <h5 onclick ="popupChat(this)"  id="{{$group->id}}" title ="group">{{$group->name}}</h5>
                                </li>
                            @endforeach
                        </ul>
                    @endif
            </div>
        </div>
    </div>


@endsection