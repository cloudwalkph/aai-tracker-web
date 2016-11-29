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

        .card-container {
            display: inline-block;
        }

        .card-image {
            width: 100%;
            height: 300px;
            background-color: #dddddd;
            display: table;
            text-align: center;
        }

        .card-image > .hit-statistics {
            display: table-cell;
            vertical-align: middle;
        }

        a:hover {
            text-decoration: none;
        }

        .hit-statistics {
            width: 100%;
            height: 100%;
            font-size: 25px;
            font-weight: 500;
            color: #646363;
            padding: 0;
        }

        .hit-statistics li {
            list-style: none;
        }

        .not-started {
            color: #646363;
        }

        .finished {
            color: #802221;
        }

        .on-going {
            color: #2f8030;
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
            position: relative;
            cursor: pointer;
        }

        .event-status {
            position: absolute;
            top: 30px;
            left: 50%;
            transform: translate(-50%, -50%);
            font-weight: 600;
            font-size: 35px;
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

@section('page-js')
    @if (count($events))
        <script type="application/javascript">
            var source = new EventSource('/api/v1/events/hits');
            var hitsWorker = new Worker('/js/hits-updater.js');

            source.addEventListener("message", function(res) {
                var jsonData = JSON.parse(res.data);

                hitsWorker.postMessage(jsonData);
            }, false);

            hitsWorker.onmessage = function(e) {
                var element = document.getElementById(e.data.id);
                element.innerHTML = e.data.event.total_hits;
            };
        </script>
    @endif
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
                    <div class="card-container col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
                        <div class="row">
                            @foreach ($events as $event)
                                <div class="col-md-4 col-sm-6 col-xs-12 card-item">
                                    <h4 class="event-status {{ $event['status-slug'] }}">{{ $event['status'] }}</h4>

                                    <a href="/insite/events/{{ $event['id'] }}">
                                        <div class="card-image">
                                            <ul class="hit-statistics">
                                                <li>
                                                    Hits <br>
                                                    <span id="hits-{{ $event['id'] }}">500</span>/<span>1000</span>
                                                </li>
                                            </ul>
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
