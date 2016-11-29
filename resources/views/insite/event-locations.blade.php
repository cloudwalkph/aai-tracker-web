@extends('layouts.insite')

@section('page-css')
    <link rel="stylesheet" href="/lib/nvd3/nvd3.css">
    <style>
        .dasboard {
            margin-top: 80px;
        }

        #card-container {
            margin-top: 200px;
            display: inline-block;
            border-left: 1px solid #dddddd;
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

        #pieChartContainer1 {
            width: 100%;
            height: 400px;
        }

        #pieChartContainer2 {
            width: 100%;
            height: 400px;
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


    @if ($event)
        <script type="application/javascript">
            var source = new EventSource('/api/v1/events/{{ $event->id }}/answers');
            source.addEventListener("message", function(res) {
                var jsonData = JSON.parse(res.data);

                drawChart('#pieChartContainer1 svg', jsonData.data['Gender']);
                drawChart('#pieChartContainer2 svg', jsonData.data['Age Group']);
            }, false)
        </script>
    @endif
@endsection

@section('content')
    <div class="container-fluid dasboard">
        <div class="row">
            <div class="col-md-12">

                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="col-md-12">
                            <div id="pieChartContainer1">
                                <svg></svg>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div id="pieChartContainer2">
                                <svg></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Event List -->
                    <div id="card-container" class="col-md-7 col-sm-12 col-xs-12">
                        <div class="row">
                            @foreach ($locations as $location)
                                <div class="col-md-4 col-sm-6 col-xs-12 card-item">
                                    <a href="/insite/events/{{ $event['id'] }}/locations/{{ $location['id'] }}">
                                        <div class="card-image">

                                        </div>
                                    </a>

                                    <div class="card-label">
                                        <p><strong>{{ $location['name'] }}</strong></p>
                                        {{--<p>{{ $event['description'] }}</p>--}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
