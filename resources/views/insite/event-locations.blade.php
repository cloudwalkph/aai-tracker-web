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
            border-left: 1px solid #c3c3c3;
        }

        .card-image {
            width: 100%;
            height: 300px;
            background-color: #c3c3c3;
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


    @if ($event)
        <script type="application/javascript">
            d3.json("/api/v1/events/{{ $event->id }}/answers", function (data) {
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

                    // Add Title
                    d3.select(container)
                            .append("text")
                            .attr("x", 200)
                            .attr("y", 100)
                            .attr("text-anchor", "middle")
                            .text("Sample Charts");

                    nv.utils.windowResize(chart.update);

                    return chart;
                });
            }

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
