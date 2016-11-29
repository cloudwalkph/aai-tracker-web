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

        return chart;
    });
}