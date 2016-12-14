@extends('layouts.insite')

@section('page-css')
    <link rel="stylesheet" href="/lib/nvd3/nvd3.css">
    <link href="http://vjs.zencdn.net/5.8.8/video-js.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">

    <style>
        .dasboard {
            margin-top: 5px;
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

        #timeChart {
            width: 100%;
            height: 180px;
        }

        #timeChart svg {
            background-color: #fbfbfb;
        }

        .video-feed {
            width: 600px;
            height: 360px;
            background-color: #777777;
        }

        .logo {
            width: 100%;
            margin-bottom: 20px;
            text-align: center;
            position: fixed;
            bottom: 0;
        }

        .logo img {
            width: 100%;
            max-width: 150px;
        }

        #logout {
            display: inline;
            font-size: 25px;
            text-decoration: underline;
            cursor: pointer;
            color: #fff;
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
            color: #f47f20;
            background-color: #f47f20;
        }

        .title {
            text-align: center;
            font-size: 25px;
            color: #cf7027;
            padding: 0;
            margin: 0;
        }
    </style>
@endsection

@section('page-js')
    <script src="/js/d3.js"></script>
    <script src="/lib/nvd3/nvd3.min.js"></script>
    <script src="/js/drawchart.js"></script>
    <script src="http://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
    <script src="http://vjs.zencdn.net/5.8.8/video.js"></script>
    <script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>

    <script type="application/javascript">
        var source = new EventSource('/api/v1/events/{{ $event->id }}/locations/{{ $location->id }}/answers');
        var timestampedSource = new EventSource('/api/v1/events/hits/{{ $event->id }}/locations/{{ $location->id }}/timestamped');

        var genderData;
        var ageGroupData;
        var timeChartData;

        source.addEventListener("message", function(res) {
            var jsonData = JSON.parse(res.data);
            console.log(jsonData);

            if (genderData !== JSON.stringify(jsonData.data['Gender'])) {
                drawChart('#pieChartContainer1 svg', jsonData.data['Gender'], 'Gender Proportion');

                genderData = JSON.stringify(jsonData.data['Gender']);
            }

            if (ageGroupData !== JSON.stringify(jsonData.data['Age Group'])) {
                drawChart('#pieChartContainer2 svg', jsonData.data['Age Group'], 'Age Group');

                ageGroupData = JSON.stringify(jsonData.data['Age Group']);
            }

        }, false);

        timestampedSource.addEventListener("message", function(res) {
            var jsonData = JSON.parse(res.data);

            if (timeChartData !== JSON.stringify(jsonData.data)) {
                drawTimeChart('#timeChart svg', jsonData.data);

                timeChartData = JSON.stringify(jsonData.data);
            }
        });
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
                        <input type="hidden" value="{{ $location->id }}" id="locationId" />
                        <input type="hidden" value="{{ $event->id }}" id="eventId" />
                    </div>
                </div>

                <div class="row" style="margin-bottom: 20px;">
                    <hr class="orenji">
                    <div class="col-md-5 col-sm-12 col-xs-12">

                        <div class="video-feed">
                            {{--<img src="http://{{ $location->ip }}:81/videostream.cgi?user=admin&pwd=888888" alt="">--}}
                            <video id="my-video" class="video-js" controls preload="auto" autoplay width="600" height="360" data-setup="{}">
                                <source src="rtmp://54.238.155.160/live/{{ $location->channel }}" type='rtmp/mp4'>
                                <p class="vjs-no-js">
                                    To view this video please enable JavaScript, and consider upgrading to a web browser that
                                    <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                                </p>
                            </video>
                        </div>
                        <div style="text-align: center">
                            <a href="http://miitown.com" target="_blank">View GPS Data</a>
                        </div>
                    </div>

                    <div class="col-md-7 col-sm-12 col-xs-12" style="margin-bottom: 20px">
                        <div class="col-md-6">
                            <div id="pieChartContainer1">
                                <h1 class="title">Gender Proportion</h1>
                                <svg></svg>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div id="pieChartContainer2">
                                <h1 class="title">Age Group</h1>
                                <svg></svg>
                            </div>
                        </div>

                        <div class="col-md-12 col-xs-12">
                            <div id="timeChart" style="margin-top: 30px">
                                <h1 class="title" style="text-align: left">Timestamp</h1>
                                <svg></svg>
                            </div>
                        </div>
                    </div>

                    <div style="clear: both;"></div>
                    <hr class="orenji">
                </div>

                <div class="modal fade answersModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Hits</h4>
                            </div>
                            <div class="modal-body">
                                <div class="loading-hits" style="text-align: center">
                                    <p>Getting data from the server</p>
                                </div>

                                <table id="hitsTable" style="display: none">

                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

                <div class="logo col-xs-12 col-md-12">
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
