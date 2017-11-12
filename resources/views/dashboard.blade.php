@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <div id="game">
                <div class="tab">
                    <span id="userName"></span>
                    <button class="tablinks" onclick="openCity(event, 'London')">Chat</button>
                    <button class="tablinks" onclick="openCity(event, 'Paris')">Game</button>
                </div>

                <div id="London" class="tabcontent">
                    <div id="message" style="height:100%;" class="invisible">
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
                        <h1>Game</h1>
                </div>
                </div>

            </div>
            <div class="col-md-3" style="background:#4C516D;color:white;height:600px;">
                <div style="text-align:right"><i class="fa fa-bell" style="font-size:20px;margin-top:5px;" aria-hidden="true"></i></div>
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