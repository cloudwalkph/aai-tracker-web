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