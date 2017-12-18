@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9" style="height:650px;border:1px solid #aaa">
                <!--
                ***********************************************************************************************
                This is dashboard stuff, displayed when user first land on the page or refresh the page
                ***********************************************************************************************
                -->

                <div id="stat">
                    <div id="donutchart" style="width: 100%; height: 100%"></div>
                </div>
                <!--
                ***********************************************************************************************
                Statistic ends here
                ***********************************************************************************************
                -->
                <!--
                ***********************************************************************************************
                This section is user's work area. This section contains game related data and renders all the
                chats between individual users and group. When user first arrives to this page this section is
                hidden. Once a user click on other member link this section becomes available displaying chats
                between the two. The the user can switch between chats and games.
                ***********************************************************************************************
                -->
                <div id="game" class="invisible" style="width: 100%; height: 100%; overflow:hidden">
                    <div class="tab">
                        <span  id="userName"></span>
                        <span class="inviteThisUser invisible" id="inviteThisUser">
                            <i class="fa fa-bullseye" aria-hidden="true" style="font-size: 24px;cursor:pointer;" title= 'Challenge This User' onclick="manager.invite(this)"></i>
                            </span>
                    </div>

                    <div class="row">

                        <div id="game-section" class="">

                            <!--
                             ***********************************************************************************************
                             Show this section when the user has not invited the viewed user to play.
                             ***********************************************************************************************
                            -->
                        </div>

                        <div id="chat-section" class="" style="position:relative">
                            <div id="message" style="height:100%;margin-top:40px;" >
                                <div>
                                    <div style="padding:5px;width:100%;height:535px;overflow:hidden;" class="chatBox">
                                        <div>
                                            <div class="chatMessage" style="height:430px; overflow-x:hidden; overflow-y:auto;">
                                            </div>
                                            <div style="width:100%;color:#000;text-align:right">
                                                <input style="border:none;width:100%;height: 50px;margin-top:10px;margin-bottom: 5px; padding:10px
                                                ;background:rgba(141,163,153,.4)border-radius: 5px;"
                                                       type="text" name="message" class="message" id ="messageText"  placeholder="Type your message">
                                                <button onclick="messages.send(this)" style="border:1px solid #080808;
                                             -webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius:
                                             5px;background-color: #337ab7">Send</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <input id="receiver_id" type="hidden" >
                    <input id="this_user"  type="hidden">
                    <input id="lst_saved" type = "hidden">
                    <input id="gameID" type="hidden">

                </div>
                <!--
                ***********************************************************************************************
                    Game section ends here
                ***********************************************************************************************
                -->
            </div>



            <div class="col-md-3" style="
            background:#4C516D;color:white;height:650px;">

                <div style="text-align:right">@if(count($challenges)>0)<span style="font-size:18px">{{count($challenges)}}</span> @endif
                    <i class="fa fa-bell" style="font-size:20px;margin-top:5px;" aria-hidden="true" onclick="manager.showChallenges(this)"></i>
                    </div>

                @if(count($challenges)>0)

                <div id="showChallengers" class="invisible" style="background:rgba(255,255,255,1);color:#000;z-index:100;
                border:0.5px solid #444;border-radius:10px;opacity:.4">
                <ul>
                @foreach($challenges as $n)
                    <li style="list-style:none;width:300px;cursor:pointer" id="{{ $n['challenger_id'] }}" onclick="manager.acceptChallenge(this)" >
                        {{ $n['challenger'] }} <i class="fa fa-times" aria-hidden="true"></i></li>
                @endforeach
                    </ul>
                 </div>
                @endif

                Users
                <div style="width:100%;height:250px;overflow-y:auto;overflow-x:hidden; border-radius: 20px">
                @if(count($users)>0)
                    <ul style=" #FFFFFF;">
                        @foreach($users as $user)
                            <li style="list-style:none;width:300px;cursor:pointer">
                                <h5 id="{{ $user['id'] }}" onclick="manager.getThisUserData(this)" title="individual">
                                    <img src="{{$user['image_url']}}"  style="height:30px;width:30px"/> {{$user['name']}}
                                </h5>
                            </li>
                        @endforeach
                    </ul>
                @endif
                </div>
                <hr>
                    ChatRooms/ Groups
                <div style="width:100%;height:200px;overflow-y:auto;overflow-x:hidden;border-radius: 20px">
                    @if(count($groups)>0)
                        <ul style="#FFFFFF;">
                            @foreach($groups as $group)
                                <li style="list-style:none;width:300px;cursor:pointer">
                                    <h5 onclick ="manager.getThisUserData(this)"  id="{{$group['id']}}" title ="group">{{$group['name']}}</h5>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>


@endsection