onmessage = function(e) {
    var events = e.data.data;

    for (var i = 0; i < events.length; i++) {
        var event = events[i];
        var id = 'hits-' + event.id;

        var data = {
            id: id,
            event: event
        };

        postMessage(data);
    }
};