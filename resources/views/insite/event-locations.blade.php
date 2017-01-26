@extends('layouts.insite')

@section('page-css')
    <link rel="stylesheet" href="/lib/nvd3/nvd3.css">
    <link rel="stylesheet" type="text/css" href="/lib/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="/lib/slick/slick-theme.css"/>

    <style>
        .dasboard {
            margin-top: 10px;
            height: 1vh%;
        }

        .card-container {
            margin-top: 150px;
            display: inline;
            overflow: hidden;
            padding: 0 65px;
            min-height: 350px;
            max-height: 350px;
        }

        .card-image {
            height: 300px;
            width: 100%;
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

        .card-label {
            margin-top: 20px;
        }

        .card-label p {
            line-height: 0.7em;
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
        }

        .card-item {
            padding: 10px 10px;
            height: 100%;
            width: 100%;
            cursor: pointer;
            float: left;
        }

        #pieChartContainer1 {
            width: 100%;
            height: 300px;
        }

        #pieChartContainer2 {
            width: 100%;
            height: 300px;
        }

        .event-title {
            margin-left: 20px;
            font-size: 25px;
        }

        .charts {
            border-right: 1px #000;
        }

        .logo {
            width: 100%;
            margin-bottom: 20px;
            text-align: center;
            /*position: fixed;*/
            bottom: 0;
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
            color: #fff;
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
    <script type="text/javascript" src="/lib/slick/slick.min.js"></script>
    <script src="/js/d3.js"></script>
    <script src="/lib/nvd3/nvd3.min.js"></script>
    <script src="/js/drawchart.js"></script>

    @if ($event)
        <script type="application/javascript">
            {{--var source = new EventSource('/api/v1/events/{{ $event->id }}/answers');--}}
            var hitSource = new EventSource('/api/v1/events/hits/{{ $event->id }}');
            var hitsWorker = new Worker('/js/hits-updater.js');
//
//            var genderData;
//            var ageGroupData;
            var hitsData;

//            source.addEventListener("message", function(res) {
//                var jsonData = JSON.parse(res.data);
//
//                if (genderData !== JSON.stringify(jsonData.data['Gender'])) {
//                    drawChart('#pieChartContainer1 svg', jsonData.data['Gender']);
//
//                    genderData = JSON.stringify(jsonData.data['Gender']);
//                }
//
//                if (ageGroupData !== JSON.stringify(jsonData.data['Age Group'])) {
//                    drawChart('#pieChartContainer2 svg', jsonData.data['Age Group']);
//
//                    ageGroupData = JSON.stringify(jsonData.data['Age Group']);
//                }
//            }, false);

            // Hits
            hitSource.addEventListener("message", function(res) {
                var jsonData = JSON.parse(res.data);

                if (hitsData !== JSON.stringify(jsonData)) {
                    hitsWorker.postMessage(jsonData);

                    hitsData = JSON.stringify(jsonData);
                }

            }, false);

            hitsWorker.onmessage = function(e) {
                var element = document.getElementById(e.data.id);
                element.innerHTML = e.data.event.total_hits;
            };

            $(function() {
                $('.events').slick({
                    infinite: true,
                    slidesToShow: 3,
                    slidesToScroll: 3
                });
            });
        </script>
    @endif
@endsection

@section('content')
    <div class="container-fluid dasboard">
        <div class="row">
            <div class="col-md-12">
                <div class="row" style="margin-bottom: 40px">
                    <div class="col-md-4">
                        <h1 class="event-title">{{ $event['name'] . ' : ' . $event['description'] }}</h1>
                    </div>
                </div>

                <div class="row">
                    {{--<div class="col-md-4 col-sm-12 col-xs-12 charts">--}}
                        {{--<div class="col-md-12">--}}
                            {{--<div id="pieChartContainer1">--}}
                                {{--<svg></svg>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="col-md-12">--}}
                            {{--<div id="pieChartContainer2">--}}
                                {{--<svg></svg>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    <!-- Event List -->
                    <div class="card-container col-md-12 col-sm-12 col-xs-12">
                        <div class="events">
                            @foreach ($locations as $location)
                                <div class="card-item">
                                    <a href="/insite/events/{{ $event['id'] }}/locations/{{ $location['id'] }}">
                                        <div class="card-image">
                                            <ul class="hit-statistics">
                                                <li>
                                                    Hits <br>
                                                    <span id="hits-{{ $location['id'] }}">0</span>/<span>{{ $location['expected_hits'] }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </a>

                                    <div class="card-label">
                                        <p><strong>{{ $location['name'] }}</strong></p>
                                        <p>{{ \Carbon\Carbon::createFromTimestamp(strtotime($location->live_date))->toFormattedDateString() }}</p>
                                        {{--<p>{{ $event['description'] }}</p>--}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="logo">
                    <a href="/insite">
                        <img src="/logo-verify.png" alt="Activations Insite logo">
                    </a> <br>
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
