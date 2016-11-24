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
    <script src="http://d3js.org/d3.v3.min.js"></script>
    <script src="/lib/nvd3/nvd3.min.js"></script>

    <script type="application/javascript">
        d3.json("/api/v1/events/{{ $event->id }}/locations/{{ $location->id }}/answers", function (data) {
            drawChart('#pieChartContainer1 svg', data.data['Gender']);
            drawChart('#pieChartContainer2 svg', data.data['Age Group']);
        });

        function drawChart(container, data) {
            nv.addGraph(function() {
                var chart = nv.models.pieChart()
                        .x(function(d) { return d.key })
                        .y(function(d) { return d.values })
                        .showLabels(true)
                        .labelThreshold(.05)
                        .labelType("key")
                        .labelsOutside(true);

                var chartData = d3.nest()
                        .key(function(d) { return d.label })
                        .rollup(function(d) {
                            return d3.sum(d, function(g) { return g.answer });
                        }).entries(data);

                d3.select(container)
                        .datum(chartData)
                        .transition().duration(350)
                        .call(chart);

                nv.utils.windowResize(chart.update);

                return chart;
            });
        }

    </script>
@endsection

@section('content')
    <div class="container-fluid dasboard">
        <div class="row">
            <div class="col-md-12">

                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="video-feed">

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
