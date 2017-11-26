<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ConnectMe') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://use.fontawesome.com/0d79e5b86d.js"></script>

    <style>
        body{
            height:100%;
        }

        /* Style the tab */
        div.tab {
            overflow: hidden;
            border: 1px solid #ccc;
            border-radius: 5px;
           // background-color: #f1f1f1;
            background-color: #4C516D;
            color:#fff;
            padding: 14px 16px;
            transition: 0.3s;
        }

        /* Style the buttons inside the tab */
        .inviteThisUser {
            background-color: inherit;
            float: right;
            border: none;
            outline: none;
            cursor: pointer;
            transition: 0.3s;
        }

        /* Change background color of buttons on hover */
        div.tab button:hover {
            background:rgba(141,163,153,.4);
        }

        /* Create an active/current tablink class */
        div.tab button.active {
            background:rgba(141,163,153,.4);
        }
        /*username style*/
        div.tab span {
            font-size:20px;
            margin-left: 15px;
        }

        /* Style the tab content */
        .tabcontent {
            display: none;
            padding: 6px 12px;
            //border: 1px solid #ccc;
            border-top: none;
        }
        .invisible{
            display:none;

        }
        #stat{
            position: relative;
            border: 0.5px solid rgba(141,163,153,.4);
            height: 600px;
        }
        #stat #gameplayed{
            position: absolute;
            top: 20px;
            left: 20px;
        }
        #stat #gamewon{
            position: absolute;
            top: 20px;
            right: 20px;
        }
        #stat #gameloss{
            position: absolute;
            top: 140px;
            left: 20px;
        }
        #stat #gamedrawn{
            position: absolute;
            top: 140px;
            right: 20px;
        }
        #stat #rating{
            position: absolute;
            top: 260px;
            left: 30%;
        }

        .statbox{
            font-size: 20px;
            height:100px;
            background:rgba(255,255,255,.9);
            color:#4C516D;
            border-radius:10px;
            border:0.5px dashed #4C516D;
            width: 40%;
            position:relative;
            padding-left: 100px;
            padding-top: 30px;
            margin-top: 120px;
        }

        .statbox span{
            font-size: 24px;
            letter-spacing: 1px;

        }
        #dice {font-size: 6rem;}
        .cells_white{
            fill: none;
            stroke-width: 1px; stroke: #4C516D;
        }
    </style>
</head>
<body>
<div id="app">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/dashboard') }}">
                    {{ config('app.name', 'ConnectMe') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                                <li disabled>
                                    <a href="#">User Setting</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')
</div>

<!-- Scripts -->

<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/util.js') }}"></script>
<script src="{{ asset('js/Objects/Cells.js') }}"></script>
<script src="{{ asset('js/Objects/Pieces.js') }}"></script>
<script src="{{ asset('js/manager.js') }}"></script>
<script src="{{ asset('js/messages.js') }}"></script>
<script src="{{ asset('js/game.js') }}"></script>


</body>
</html>
