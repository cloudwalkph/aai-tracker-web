@extends('layouts.insite')

@section('page-css')
    <link rel="stylesheet" href="/lib/nvd3/nvd3.css">
    <style>
        .dasboard {
            margin-top: 10px;
        }

        .card-label p {
            line-height: 0.7em;
            text-align: center;
            font-size: 20px;
        }

        #pieChartContainer1 {
            width: 100%;
            height: 300px;
        }

        #pieChartContainer2 {
            width: 100%;
            height: 300px;
        }

        .video-feed {
            width: 100%;
            height: 500px;
            background-color: #777777;
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

        .event-title {
            margin-left: 20px;
            font-size: 25px;
            font-weight: 600;
            text-align: center;
        }

        .event-location {
            font-size: 20px;
            text-align: center;
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
    <script src="/js/d3.js"></script>
    <script src="/lib/nvd3/nvd3.min.js"></script>
    <script src="/js/drawchart.js"></script>

    <script type="application/javascript">
        var source = new EventSource('/api/v1/events/{{ $event->id }}/locations/{{ $location->id }}/answers');
        source.addEventListener("message", function(res) {
            var jsonData = JSON.parse(res.data);

            drawChart('#pieChartContainer1 svg', jsonData.data['Gender']);
            drawChart('#pieChartContainer2 svg', jsonData.data['Age Group']);
        }, false)
    </script>
@endsection

@section('content')
    <div class="container-fluid dasboard">
        <div class="row">
            <div class="col-md-12">
                <div class="row" style="margin-bottom: 10px">
                    <div class="col-md-12 col-xs-12">
                        <h1 class="event-title">{{ $event['name'] . ' : ' . $event['description'] }}</h1>
                        <p class="event-location">{{ $location->name }}</p>
                    </div>
                </div>

                <div class="row">
                    <hr>
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="video-feed">
                            <img src="http://{{ $location->ip }}:81/videostream.cgi?user=admin&pwd=888888" alt="">
                        </div>
                    </div>

                    <div class="col-md-8 col-sm-12 col-xs-12">
                        <div class="col-md-6">
                            <div id="pieChartContainer1">
                                <svg></svg>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div id="pieChartContainer2">
                                <svg></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="logo col-xs-12 col-md-12">
                    <hr>
                    <img src="/logo.png" alt="Activations Insite logo"> <br>
                    <a href="{{ url('/logout') }}" id="logout"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        Logout
                    </a>

                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                    
                    <div>
                        <a href="/insite/events/{{ $event->id }}/">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
