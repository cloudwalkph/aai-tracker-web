function drawChart(container, data, title) {
    nv.addGraph(function() {
        var chart = nv.models.pieChart()
            .x(function(d) { return d.key })
            .y(function(d) { return d.values })
            .color(['#532400', '#f37f20', '#c76819', '#9f530c', '#7a3d00'])
            .showLabels(true)
            .labelSunbeamLayout(true)
            .labelsOutside(true)
            .labelThreshold(.05)
            .title(title)
            .titleOffset(23)
            .noData('There is no data coming from the server yet');

        var chartData = d3.nest()
            .key(function(d) { return d.label })
            .rollup(function(d) {
                return d3.sum(d, function(g) { return g.answer });
            }).entries(data);

        d3.select(container)
            .datum(chartData)
            .transition().duration(350)
            .call(chart);

        d3.selectAll('.nv-legend')
            .attr('transform', 'translate(-50, 0)')
            .attr('text-align', 'center');

        nv.utils.windowResize(chart.update);

        chart.pie.dispatch.on("elementClick", function(e) {
            window.keySelected = e.data.key;

            $('.answersModal').modal('show');
        });

        return chart;
    });
}

function drawTimeChart(container, data) {
    nv.addGraph(function() {
        var chart = nv.models.lineWithFocusChart()
            .color(['#aa7c00'])
            .showLegend(false);

        var customTimeFormat = d3.time.format.multi([
            ["%-I:%M %p", function(d) { return d.getMinutes(); }],
            ["%-I %p", function(d) { return d.getHours(); }],
            ["%b %-d", function(d) { return d.getDate() != 1; }],
            ["%b %-d", function(d) { return d.getMonth(); }],
            ["%Y", function() { return true; }]
        ]);

        chart.xScale(d3.time.scale());
        chart.xAxis
            .showMaxMin(false)
            .axisLabel('Time')
            .tickFormat(function(d) {
                return customTimeFormat(new Date(d * 1000));
            });

        chart.x2Axis
            .showMaxMin(false)
            .axisLabel('Time')
            .tickFormat(function(d) {
                return customTimeFormat(new Date(d * 1000));
            });

        chart.yAxis
            .tickFormat(d3.format("d"));

        chart.y2Axis
            .tickFormat(d3.format("d"));

        d3.select(container)
            .datum(data)
            .transition().duration(500)
            .call(chart);

        nv.utils.windowResize(chart.update);

        return chart;
    });
}

function drawChartAdvance(container, data) {
    var svg = d3.select(container)
        .append("g");

    var chartData = d3.nest()
        .key(function(d) { return d.label })
        .rollup(function(d) {
            return d3.sum(d, function(g) { return g.answer });
        }).entries(data);

    console.log(chartData);

    svg.append("g")
        .attr("class", "slices");
    svg.append("g")
        .attr("class", "labels");
    svg.append("g")
        .attr("class", "lines");

    var width = 200,
        height = 300,
        radius = Math.min(width, height) / 2;

    var pie = d3.layout.pie()
        .sort(null)
        .value(function(d) {
            return d.values;
        });

    var arc = d3.svg.arc()
        .outerRadius(radius * 0.8)
        .innerRadius(radius * 0.4);

    var outerArc = d3.svg.arc()
        .innerRadius(radius * 0.9)
        .outerRadius(radius * 0.9);

    svg.attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    var key = function(d){ return d.data.key; };

    var color = d3.scale.category20();
    //     .domain(["Male", "Female"]);
    //.range(["#98abc5", "#8a89a6", "#7b6888", "#6b486b", "#a05d56", "#d0743c", "#ff8c00"]);

    change(chartData);

    function mergeWithFirstEqualZero(first, second){
        var secondSet = d3.set(); second.forEach(function(d) { secondSet.add(d.key); });

        var onlyFirst = first
            .filter(function(d){ return !secondSet.has(d.label) })
            .map(function(d) { return {key: d.label, values: 0}; });
        return d3.merge([ second, onlyFirst ])
            .sort(function(a,b) {
                return d3.ascending(a.key, b.key);
            });
    }

    function change(data) {
        var duration = +2500;
        var data0 = svg.select(".slices").selectAll("path.slice")
            .data().map(function(d) { return d.data });
        if (data0.length == 0) data0 = data;
        var was = mergeWithFirstEqualZero(data, data0);
        var is = mergeWithFirstEqualZero(data0, data);

        /* ------- SLICE ARCS -------*/

        var slice = svg.select(".slices").selectAll("path.slice")
            .data(pie(was), key);

        slice.enter()
            .insert("path")
            .attr("class", "slice")
            .style("fill", function(d) { return color(d.data.key); })
            .each(function(d) {
                this._current = d;
            });

        slice = svg.select(".slices").selectAll("path.slice")
            .data(pie(is), key);

        slice
            .transition().duration(duration)
            .attrTween("d", function(d) {
                var interpolate = d3.interpolate(this._current, d);
                var _this = this;
                return function(t) {
                    _this._current = interpolate(t);
                    return arc(_this._current);
                };
            });

        slice = svg.select(".slices").selectAll("path.slice")
            .data(pie(data), key);

        slice
            .exit().transition().delay(duration).duration(0)
            .remove();

        /* ------- TEXT LABELS -------*/

        var text = svg.select(".labels").selectAll("text")
            .data(pie(was), key);

        text.enter()
            .append("text")
            .attr("dy", ".35em")
            .style("opacity", 0)
            .text(function(d) {
                return d.data.key;
            })
            .each(function(d) {
                this._current = d;
            });

        function midAngle(d){
            return d.startAngle + (d.endAngle - d.startAngle)/2;
        }

        text = svg.select(".labels").selectAll("text")
            .data(pie(is), key);

        text.transition().duration(duration)
            .style("opacity", function(d) {
                return d.data.values == 0 ? 0 : 1;
            })
            .attrTween("transform", function(d) {
                var interpolate = d3.interpolate(this._current, d);
                var _this = this;
                return function(t) {
                    var d2 = interpolate(t);
                    _this._current = d2;
                    var pos = outerArc.centroid(d2);
                    pos[0] = radius * (midAngle(d2) < Math.PI ? 1 : -1);
                    return "translate("+ pos +")";
                };
            })
            .styleTween("text-anchor", function(d){
                var interpolate = d3.interpolate(this._current, d);
                return function(t) {
                    var d2 = interpolate(t);
                    return midAngle(d2) < Math.PI ? "start":"end";
                };
            });

        text = svg.select(".labels").selectAll("text")
            .data(pie(data), key);

        text
            .exit().transition().delay(duration)
            .remove();

        /* ------- SLICE TO TEXT POLYLINES -------*/

        var polyline = svg.select(".lines").selectAll("polyline")
            .data(pie(was), key);

        polyline.enter()
            .append("polyline")
            .style("opacity", 0)
            .each(function(d) {
                this._current = d;
            });

        polyline = svg.select(".lines").selectAll("polyline")
            .data(pie(is), key);

        polyline.transition().duration(duration)
            .style("opacity", function(d) {
                return d.data.value == 0 ? 0 : .5;
            })
            .attrTween("points", function(d){
                this._current = this._current;
                var interpolate = d3.interpolate(this._current, d);
                var _this = this;
                return function(t) {
                    var d2 = interpolate(t);
                    _this._current = d2;
                    var pos = outerArc.centroid(d2);
                    pos[0] = radius * 0.95 * (midAngle(d2) < Math.PI ? 1 : -1);
                    return [arc.centroid(d2), outerArc.centroid(d2), pos];
                };
            });

        polyline = svg.select(".lines").selectAll("polyline")
            .data(pie(data), key);

        polyline
            .exit().transition().delay(duration)
            .remove();
    }
}