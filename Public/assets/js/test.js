var helper = {
    getByClass: function(className, count) {
        var x = document.getElementsByClassName(className);
        return x[count];
    },
    getById: function($id) {
        return document.getElementById(id);
    }
};

var waitFor = {
    pageClass: function(className, count, callback) {
        var interval = setInterval(function() {
            var classObj = helper.getByClass(className, count);
            console.log(classObj);
            if (typeof classObj != 'undefined') {
                callback();
                clearInterval(interval);
            }
        }, 1000)
    }
};

function EventManager() {
    this.handlers = [];  // observers
}

EventManager.prototype = {
    on: function(name, fn) {
        var event = new PageEvent(name, fn, this);
        this.handlers.push(event);
    },
    off: function(fn) {
        this.handlers = this.handlers.filter(
            function(item) {
                if (item !== fn) {
                    return item;
                }
            }
        );
    },
    fire: function(o) {
        this.handlers.forEach(function(item) {
            item.run(o);
        });
    },
    debug: function() {
        console.log('ololo');
    }
};

function PageEvent(name, callback, manager) {
    this.name = name;
    this.callback = callback;
    this.eventManager = manager;
}

PageEvent.prototype = {
    run: function(eventName) {
        if (eventName == this.name) {
            this.callback();
        }
    },
    fire: function(eventName) {
        this.eventManager.fire(eventName);
    }
};

var eventManager = new EventManager();
eventManager.on('event.page_loaded', function(event) {
    waitFor.pageClass('ololo', 0, function() {
        eventManager.fire('event.events_page_loaded');
    });
});

eventManager.on('event.events_page_loaded', function(event) {
    console.log('EVENTS');
});

eventManager.fire('event.page_loaded');

