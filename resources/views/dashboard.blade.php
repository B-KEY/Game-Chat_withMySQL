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
                        <div style="border:2px solid #090909;padding:5px;margin-left:10px; width:100%;height:600px;overflow:hidden;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;" class="chatBox">
                            <div style="border:1px solid #337ab7;padding-left:5px;border-radius:2px;background-color: #1b6d85;color:#fff">
                                <h5>Some User</h5>
                            </div>
                            <div>
                                <div class="chatMessage" style="height:470px">
                                </div>
                                <div style="width:100%;color:#000;text-align:right">
                                    <input style="border:none;width:100%;height:40px;margin-top:5px;margin-bottom: 5px;" type="text" name="message" class="message">
                                    <button onclick="send(this)" style="border:1px solid #080808; -webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;background-color: #337ab7">Send</button>
                                    <input type="hidden" value="'+id+'"></div>
                            </div>
                        </div>
                </div>
            </div>
            </div>
            <div class="col-md-3">

                @if(count($users)>0)
                    <ul style="border:2px solid #FFFFFF;border-radius:10px">
                        @foreach($users as $user)
                            <li style="list-style:none;width:300px">
                                <h5>{{$user->name}}
                                    <span style="float:right;right:40px;position:absolute">
                                        <img title="{{$user->id}}" onclick = "popupChat(this)"
                                             src="images/message.png"  style="height:15px;width:20px"/> <span> | </span>
                                        <img title="{{$user->id}}" onclick = "popupGame(this)"
                                             src="images/challenge.png"  style="height:20px;width:20px"/>
                                    </span>
                                </h5>

                            </li>

                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>


@endsection