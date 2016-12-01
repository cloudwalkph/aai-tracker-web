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

        d3.selectAll('.nv-legend')
            .attr('transform', 'translate(-120, 280)')
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
        var chart = nv.models.lineWithFocusChart();

        var customTimeFormat = d3.time.format.multi([
            ["%-I:%M:%S %p", function(d) { return d.getSeconds(); }],
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