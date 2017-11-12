@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <div id="game">
                <div class="tab">
                    <button class="tablinks" onclick="openCity(event, 'London')">London</button>
                    <button class="tablinks" onclick="openCity(event, 'Paris')">Paris</button>
                    <button class="tablinks" onclick="openCity(event, 'Tokyo')">Tokyo</button>
                </div>

                <div id="London" class="tabcontent">
                    <h3>London</h3>
                    <p>London is the capital city of England.</p>
                </div>

                <div id="Paris" class="tabcontent">
                    <h3>Paris</h3>
                    <p>Paris is the capital of France.</p>
                </div>

                <div id="Tokyo" class="tabcontent">
                    <h3>Tokyo</h3>
                    <p>Tokyo is the capital of Japan.</p>
                </div>
                </div>
                <div id="message" style="height:100%;" class="invisible">
                    <div>
                        <div style="padding:5px;margin-left:10px; width:100%;height:600px;overflow:hidden;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;" class="chatBox">
                            <div style="border:1px solid #337ab7;padding-left:5px;border-radius:2px;background-color: #4C516D;color:#fff">
                                <h5 id="userName">Some User</h5>
                            </div>
                            <div>
                                <div class="chatMessage" style="height:470px; overflow-x:hidden; overflow-y:auto;">

                                </div>
                                <div style="width:100%;color:#000;text-align:right">
                                    <input style="border:none;width:100%;height: 50px;margin-top:10px;margin-bottom: 5px; padding:10px
                                                ;background:rgba(141,163,153,.4)/*#8DA399#f7e1b5*/;border-radius: 5px;"
                                           type="text" name="message" class="message" multipleline placeholder="Type your message" >
                                    <button onclick="send(this)" style="border:1px solid #080808; -webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;background-color: #337ab7">Send</button>
                                    <input id="receiver_id" type="hidden" >
                                    <input id="lst_saved" type = "hidden">
                                </div>

                            </div>
                        </div>
                </div>
            </div>
            </div>
            <div class="col-md-3" style="background:#4C516D;color:white;height:600px;">
                Users
                @if(count($users)>0)
                    <ul style=" #FFFFFF;">
                        @foreach($users as $user)
                            <li style="list-style:none;width:300px;cursor:pointer">
                                <h5 id="{{$user->id}}" onclick="popupChat(this)" title="individual">
                                    <img src="{{$user->image_url}}"  style="height:30px;width:30px"/> {{$user->name}}
                                    <span style="float:right;right:40px;position:absolute">

                                        <img id="{{$user->id}}" onclick = "popupGame(this)"
                                             src="images/challenge.png"  style="height:20px;width:20px"/>
                                    </span>

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