@extends('layouts.insite')

@section('page-css')
    <link rel="stylesheet" href="/lib/nvd3/nvd3.css">
    <style>
        .dasboard {
            margin-top: 80px;
        }

        .card-label p {
            line-height: 0.7em;
            text-align: center;
            font-size: 20px;
        }

        #pieChartContainer1 {
            width: 100%;
            height: 400px;
        }

        #pieChartContainer2 {
            width: 100%;
            height: 400px;
        }

        .video-feed {
            width: 100%;
            height: 500px;
            background-color: #777777;
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

                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="video-feed">
                            {{--<img src="http://192.168.8.103:81/videostream.cgi?user=admin&pwd=888888" alt="">--}}
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
            </div>
        </div>
    </div>
@endsection
