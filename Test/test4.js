var mainHelper = {
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

var page = require('webpage').create();
page.settings.userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36';
page.viewportSize = {
    width: 1280,
    height: 768
};
var system = require('system');

page.onResourceRequested = function(requestData, networkRequest) {
    //networkRequest.abort();
    //return false;
    if (requestData.url.search('.css') >= 0
        || requestData.url.search('image') >= 0
        || requestData.url.search('.js') >= 0
        || requestData.url.search('.gif') >= 0
        || requestData.url.search('.png') >= 0
    ) {
        return true;
    }
    // 138972083169989
    if (requestData.url.search('cover_focus') >= 0) {
        console.log('Request (#' + requestData.id + '): ' + JSON.stringify(requestData, undefined, 4));

        var data = mainHelper.urlToHashMap(requestData.url);
        var paths = requestData.url.split('?');
        data['title'] = 'Test event';
        data['description'] = 'Test description';
        data['start_time'] = 20 * 60 * 60;
        data['start_date'] = '08%2F08%2F2016';
        data['cover_id'] = '152217301845467';
        var newUrl = paths[0] + '?' + mainHelper.hashMapToUrl(data);
        console.log(newUrl);
        networkRequest.changeUrl(newUrl);
    }

    if (requestData.url.search('www.facebook.com/events/create/themes') >= 0) {
        console.log('Request (#' + requestData.id + '): ' + JSON.stringify(requestData, undefined, 4));
    }

};

page.onResourceReceived = function (res) {
    return true;
    if (res.contentType.search('css') >= 0
        || res.contentType.search('image') >= 0
        || res.contentType.search('script') >= 0
    ) {
        return true;
    }

    console.log('received: ' + JSON.stringify(res, undefined, 4));
};


page.onConsoleMessage = function(msg, lineNum, sourceId) {
    if (msg.length > 500) {
        return;
    }
    console.log('CONSOLE: ' + msg);
    if (msg.search('screenshot') == 0) {
        var name = Math.floor(Date.now() / 1000);
        name = name + '.png';
        page.render(name);
    }

    if (msg.search('exit') == 0) {
        setTimeout(function() {
            phantom.exit(code);
        }, 0);
        phantom.onError = function(){};
    }

    if (msg.search('keypress') == 0) {
        page.sendEvent('keypress', page.event.key.T, null, null, 0);
        page.sendEvent('keypress', page.event.key.E, null, null, 0);
        page.sendEvent('keypress', page.event.key.S, null, null, 0);
        page.sendEvent('keypress', page.event.key.T, null, null, 0);
        page.sendEvent('keypress', page.event.key.Tab, null, null, 0);
        page.sendEvent('keypress', page.event.key.Tab, null, null, 0);
        page.sendEvent('keypress', page.event.key[1], null, null, 0);
        page.sendEvent('keypress', page.event.key[1], null, null, 0);

        page.sendEvent('keypress', page.event.key.Space, null, null, 0);

        page.sendEvent('keypress', page.event.key[1], null, null, 0);
        page.sendEvent('keypress', page.event.key[1], null, null, 0);

        page.sendEvent('keypress', page.event.key.Space, null, null, 0);

        page.sendEvent('keypress', page.event.key[2], null, null, 0);
        page.sendEvent('keypress', page.event.key[0], null, null, 0);
        page.sendEvent('keypress', page.event.key[1], null, null, 0);
        page.sendEvent('keypress', page.event.key[6], null, null, 0);

        page.sendEvent('keypress', page.event.key.Tab, null, null, 0);
        page.sendEvent('keypress', page.event.key.Tab, null, null, 0);
        page.sendEvent('keypress', page.event.key.Tab, null, null, 0);
        page.sendEvent('keypress', page.event.key.Tab, null, null, 0);
        page.sendEvent('keypress', page.event.key.Tab, null, null, 0);
        page.sendEvent('keypress', page.event.key.Tab, null, null, 0);
        page.sendEvent('keypress', page.event.key.Tab, null, null, 0);
        page.sendEvent('keypress', page.event.key.Tab, null, null, 0);
        page.sendEvent('keypress', page.event.key.Tab, null, null, 0);
        page.sendEvent('keypress', page.event.key.Tab, null, null, 0);
        page.sendEvent('keypress', page.event.key.Tab, null, null, 0);
        page.sendEvent('keypress', page.event.key.Tab, null, null, 0);

        page.sendEvent('keypress', page.event.key.Return, null, null, 0);
    }

    if (msg.search('upload') == 0) {
        console.log('Uploading file...');
        page.uploadFile('input[name=file]', '/var/www/Test/123.jpg');
    }
};

page.onInitialized = function() {
    page.onCallback = function(data) {



        var currentUrl = page.evaluate(function() {
            return window.location.href;
        });
        console.log('Main page is loaded and ready: ' + currentUrl);

        page.injectJs('actions.js');
        page.injectJs('helper.js');
        page.injectJs('events.js');
    };

    page.evaluate(function() {
        document.addEventListener('DOMContentLoaded', function() {
            window.callPhantom();
        }, false);
    });

};

page.open("http://www.facebook.com/login.php");

setTimeout(function() {
    phantom.exit();
}, 300000);