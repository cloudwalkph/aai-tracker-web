@extends('layouts.insite')

@section('page-css')
    <style>
        .dasboard {
            margin-top: 80px;
        }

        .title-name {
            text-align: center;
            line-height: 0.7em;
        }

        #card-container {
            display: inline-block;
        }

        .card-image {
            width: 100%;
            height: 300px;
            background-color: #dddddd;
        }

        .card-label {
            margin-top: 20px;
        }

        .card-label p {
            line-height: 0.7em;
            text-align: center;
            font-size: 20px;
        }

        .card-item {
            cursor: pointer;
        }

        .logo {
            width: 100%;
            margin-bottom: 20px;
            text-align: center;
        }

        .logo img {
            width: 100%;
            max-width: 200px;
        }

        #logout {
            display: inline;
            font-size: 25px;
            text-decoration: underline;
            cursor: pointer;
            color: #646363;
        }

        hr {
            border: none;
            height: 1px;
            color: #aaa;
            background-color: #aaa;
        }
    </style>
@endsection

@section('content')
    <div class="dasboard">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <h3 class="title-name">Hi <strong>John Dela Cruz</strong></h3>
                <h4 class="title-name">Here are your Active Projects</h4>
                <hr>

                <div class="row">
                    <!-- Event List -->
                    <div id="card-container" class="col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
                        <div class="row">
                            @foreach ($events as $event)
                                <div class="col-md-4 col-sm-6 col-xs-12 card-item">
                                    <a href="/insite/events/{{ $event['id'] }}">
                                        <div class="card-image">

                                        </div>
                                    </a>

                                    <div class="card-label">
                                        <p><strong>{{ $event['name'] }}</strong></p>
                                        <p>{{ $event['description'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <hr>

                <div class="logo">
                    <img src="/images/insite-logo.png" alt="Activations Insite logo"> <br>
                    <a href="{{ url('/logout') }}" id="logout"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        Logout
                    </a>

                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
