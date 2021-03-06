@extends('layouts.insite')

@section('page-css')
    <link rel="stylesheet" href="/lib/nvd3/nvd3.css">
    {{--<link href="http://vjs.zencdn.net/5.8.8/video-js.css" rel="stylesheet">--}}
    {{--<link rel="stylesheet" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">--}}
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="//cdn.rawgit.com/noelboss/featherlight/1.6.1/release/featherlight.min.css" type="text/css" rel="stylesheet" />

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
            /*position: fixed;*/
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

        .event-location-date {
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

        .logo-image {
            width:150px;
            position: absolute;
            top: 30px;
            right: 20px;
        }

        .jw-dock-image {
            background-size: 45%;
            opacity: 1;
        }
    </style>
@endsection

@section('page-js')
    <script src="/js/d3.js"></script>
    <script src="/lib/nvd3/nvd3.min.js"></script>
    <script src="/js/drawchart.js"></script>
    {{--<script src="http://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>--}}
    {{--<script src="http://vjs.zencdn.net/5.8.8/video.js"></script>--}}
    <script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script src="//cdn.rawgit.com/noelboss/featherlight/1.6.1/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>

    {{--<script>--}}
        {{--videojs.options.flash.swf = "/VideoJS.swf";--}}
    {{--</script>--}}

    {{--JW PLAYER--}}
    <script src="//content.jwplatform.com/libraries/PotMeZLE.js"></script>
    <script>
        var player = jwplayer('player');
        var liveUrl = "rtmp://54.238.155.160/{{ $location->channel }}";

        player.setup({
            file: liveUrl,
            image: "/logo-verify.png"
        });

        player.addButton(
            //This portion is what designates the graphic used for the button
            "//icons.jwplayer.com/icons/white/download.svg",
            //This portion determines the text that appears as a tooltip
            "Download Video",
            //This portion designates the functionality of the button itself
            function() {
                //With the below code, we're grabbing the file that's currently playing
                window.location.href = player.getPlaylistItem()['file'];
            },
            //And finally, here we set the unique ID of the button itself.
            "download"
        );
    </script>

    <script type="application/javascript">
        // hits
        var hitSource = new EventSource('/api/v1/events/hits/{{ $event->id }}');
        var hitsWorker = new Worker('/js/hits-updater.js');
        var hitsData;

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

        // graphs

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

        $(function() {
            $('.answersModal').on('hidden.bs.modal', function() {
                var table = $('#hitsTable').DataTable();
                table.destroy();

                $('#hitsTable').hide();
                $('#hitsTable').empty();
            });
        });
    </script>
@endsection

@section('content')
    <div class="container-fluid dasboard">
        <div class="row">
            <div class="col-md-12">
                <div class="row" style="margin-bottom: 10px">
                    <div class="col-md-12 col-xs-12" style="position: relative">
                        <h1 class="event-title">{{ $event['name'] . ' : ' . $event['description'] }}</h1>
                        <p class="event-location">{{ $location->name }}</p>
                        <p class="event-location-date">{{ \Carbon\Carbon::createFromTimestamp(strtotime($location->live_date))->toFormattedDateString() }}</p>
                        <input type="hidden" value="{{ $location->id }}" id="locationId" />
                        <input type="hidden" value="{{ $event->id }}" id="eventId" />

                        <img src="/logo-verify.png"
                             class="logo-image"
                             alt="Activations Insite logo">
                    </div>
                </div>

                <div class="row" style="margin-bottom: 20px;">
                    <hr class="orenji">
                    <div class="col-md-5 col-sm-12 col-xs-12">

                        <div class="video-feed" id="player">


                            {{--<img src="http://{{ $location->ip }}:81/videostream.cgi?user=admin&pwd=888888" alt="">--}}
                            {{--<video id="my-video" class="video-js" controls preload="auto" autoplay width="600" height="360" data-setup="{}">--}}
                                {{--<source src="rtmp://54.238.155.160/{{ $location->channel }}" type='rtmp/mp4'>--}}
                                {{--<p class="vjs-no-js">--}}
                                    {{--To view this video please enable JavaScript, and consider upgrading to a web browser that--}}
                                    {{--<a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>--}}
                                {{--</p>--}}
                            {{--</video>--}}
                        </div>
                        <div style="text-align: left; margin-top: 20px">
                            <a type="button" class="btn btn-orange btn-solid-radius"
                               href="http://miitown.com">
                                View GPS Data
                            </a>

                            <a type="button" class="btn btn-orange btn-solid-radius"
                               href="http://miitown.com">
                                Download Video
                            </a>
                        </div>
                    </div>

                    <div class="col-md-7 col-sm-12 col-xs-12" style="margin-bottom: 20px">
                        <div class="col-md-12" style="text-align: center">
                            <h1>Total Hits: <span id="hits-{{ $location->id }}">0</span></h1>
                        </div>
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
                                    <p> <i class="fa fa-spinner"></i> Getting data from the server</p>
                                </div>

                                <table class="table" id="hitsTable" style="display: none">

                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

                <div class="logo col-xs-12 col-md-12">
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
