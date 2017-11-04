@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
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