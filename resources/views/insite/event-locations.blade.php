@extends('layouts.insite')

@section('page-css')
    <style>
        .dasboard {
            margin-top: 80px;
        }

        #card-container {
            display: inline-block;
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
    <script src="http://dimplejs.org/dist/dimple.v2.1.6.min.js"></script>

    <script type="application/javascript">
        var svg = dimple.newSvg("#pieChartContainer1", 590, 400);

        d3.tsv("/example_data.tsv", function (data) {
            var myChart = new dimple.chart(svg, data);
            myChart.setBounds(150, 0, 300, 250);
            myChart.addMeasureAxis("p", "Unit Sales");
            var ring = myChart.addSeries("Owner", dimple.plot.pie);

            ring.afterDraw = function(shape, data) {
                var g = svg.select("g");
                //find the center of the pie
                var grect =  g.node().getBBox();
                var gmidx =  grect.x + (grect.width - 7)/2;
                var gmidy =  grect.y + (grect.height - 7)/2;
                var radius = (grect.height - 7) / 2;
                //find the center of the pie-part
                var srect =  d3.select(shape).node().getBBox();
                var smidx =  srect.x + srect.width/2;
                var smidy =  srect.y + srect.height/2;
                //get the direction:
                //the parts are arranged around the center as origin (0,0)
                //so the direction is simply the mid point of the pie-part
                var dirx =   smidx;
                var diry =   smidy;
                var norm =   Math.sqrt(dirx * dirx + diry * diry);
                //normalize the direction
                dirx /= norm;
                diry /= norm;
                //multiply direction by radius to find placement for label
                //get two points: where to put label, and where to draw a line
                var x = Math.round(gmidx + (radius + 25) * dirx);
                var y = Math.round(gmidy + (radius + 15)  * diry);
                var xOnPie = Math.round(gmidx + (radius+4) * dirx);
                var yOnPie = Math.round(gmidy + (radius+4) * diry);
                //append label
                var node = svg.append("text")
                        .attr("x", x + ((dirx > 0) ? 5 : -5))
                        .attr("y", y + 3)
                        .style("font-size", "12px")
                        .style("font-family", "sans-serif")
                        .style("text-anchor", (dirx > 0) ? "start" : "end")
                        .style("fill", "black")
                        .text(data.aggField[0]);
                //append line
                svg.append("line")
                        .attr("x1", x)
                        .attr("y1", y)
                        .attr("x2", xOnPie)
                        .attr("y2", yOnPie)
                        .style("stroke", "#e0e0e0");
            };

            myChart.draw();
        });
    </script>

    <script type="application/javascript">
        var svg2 = dimple.newSvg("#pieChartContainer2", 590, 400);

        d3.tsv("/example_data.tsv", function (data) {
            var myChart2 = new dimple.chart(svg2, data);
            myChart2.setBounds(150, 0, 300, 250);
            myChart2.addMeasureAxis("p", "Unit Sales");
            myChart2.addSeries("Owner", dimple.plot.pie);
            var ring2 = myChart2.addSeries("Owner", dimple.plot.pie);

            ring2.afterDraw = function(shape, data) {
                var g = svg2.select("g");
                //find the center of the pie
                var grect =  g.node().getBBox();
                var gmidx =  grect.x + (grect.width - 7)/2;
                var gmidy =  grect.y + (grect.height - 7)/2;
                var radius = (grect.height - 7) / 2;
                //find the center of the pie-part
                var srect =  d3.select(shape).node().getBBox();
                var smidx =  srect.x + srect.width/2;
                var smidy =  srect.y + srect.height/2;
                //get the direction:
                //the parts are arranged around the center as origin (0,0)
                //so the direction is simply the mid point of the pie-part
                var dirx =   smidx;
                var diry =   smidy;
                var norm =   Math.sqrt(dirx * dirx + diry * diry);
                //normalize the direction
                dirx /= norm;
                diry /= norm;
                //multiply direction by radius to find placement for label
                //get two points: where to put label, and where to draw a line
                var x = Math.round(gmidx + (radius + 25) * dirx);
                var y = Math.round(gmidy + (radius + 15)  * diry);
                var xOnPie = Math.round(gmidx + (radius+4) * dirx);
                var yOnPie = Math.round(gmidy + (radius+4) * diry);
                //append label
                var node = svg2.append("text")
                        .attr("x", x + ((dirx > 0) ? 5 : -5))
                        .attr("y", y + 3)
                        .style("font-size", "12px")
                        .style("font-family", "sans-serif")
                        .style("text-anchor", (dirx > 0) ? "start" : "end")
                        .style("fill", "black")
                        .text(data.aggField[0]);
                //append line
                svg2.append("line")
                        .attr("x1", x)
                        .attr("y1", y)
                        .attr("x2", xOnPie)
                        .attr("y2", yOnPie)
                        .style("stroke", "#e0e0e0");
            };

            myChart2.draw();
        });
    </script>
@endsection

@section('content')
    <div class="container-fluid dasboard">
        <div class="row">
            <div class="col-md-12">

                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div id="pieChartContainer1"></div>
                        <div id="pieChartContainer2"></div>
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
