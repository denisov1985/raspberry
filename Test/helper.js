var helper = {
    getByClass: function(className, count) {
        var x = document.getElementsByClassName(className);
        return x[count];
    },
    getByAttr: function(attr, count) {
       var x = document.querySelectorAll('[' + attr + ']');
        return x[count];
    },
    getById: function($id) {
        return document.getElementById(id);
    },
    makeScreen: function(name){
        console.log('screenshot |' + name + '.png');
    },
    urlToHashMap: function(url) {
        var data = url.split('?');
        var params = data[1].split('&');
        var result = {};
        for (var i in params) {
            var item = params[i].split('=');
            result[item[0]] = item[1];
        }
        return result;
    },
    hashMapToUrl: function(obj) {
        var data = [];
        for (var i in obj) {
            data.push(i + "=" + obj[i]);
        }
        return data.join('&');
    }
};

var waitFor = {
    pageClass: function(className, count, callback) {
        var interval = setInterval(function() {
            var classObj = helper.getByClass(className, count);
            console.log(className, typeof classObj);
            helper.makeScreen('tae screen');
            if (typeof classObj != 'undefined') {
                callback();
                clearInterval(interval);
            }
        }, 2000) 
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
        console.log('Fire event: ' + o);
        this.handlers.forEach(function(item) {
            item.run(o);
        });
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
var steps = 0;
