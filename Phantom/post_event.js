var photos = {
    "data": [
        {
            "id": "136787746721756"
        },
        {
            "id": "136787740055090"
        },
        {
            "id": "136787716721759"
        },
        {
            "id": "136787710055093"
        },
        {
            "id": "136787840055080"
        },
        {
            "id": "136787810055083"
        },
        {
            "id": "136788000055064"
        },
        {
            "id": "136788213388376"
        },
        {
            "id": "136790853388112"
        },
        {
            "id": "136791203388077"
        },
        {
            "id": "136791213388076"
        },
        {
            "id": "136791220054742"
        },
        {
            "id": "136791270054737"
        },
        {
            "id": "136791290054735"
        },
        {
            "id": "136791336721397"
        },
        {
            "id": "152216995178831"
        },
        {
            "id": "152216998512164"
        },
        {
            "id": "152217005178830"
        },
        {
            "id": "152217008512163"
        },
        {
            "id": "152217038512160"
        },
        {
            "id": "152217051845492"
        },
        {
            "id": "152217055178825"
        },
        {
            "id": "152217058512158"
        },
        {
            "id": "152217085178822"
        },
        {
            "id": "152217111845486"
        },
        {
            "id": "152217115178819"
        },
        {
            "id": "152217125178818"
        },
        {
            "id": "152217155178815"
        },
        {
            "id": "152217188512145"
        },
        {
            "id": "152217191845478"
        },
        {
            "id": "152217245178806"
        },
        {
            "id": "152217258512138"
        },
        {
            "id": "152217295178801"
        },
        {
            "id": "152217301845467"
        },
        {
            "id": "152217335178797"
        }
    ]
};

var max = photos.data.length;

// ������������� Math.round() ���� ������������� �������������!
function getRandomInt(min, max)
{
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

var r = getRandomInt(0, max - 1);

var photoId = photos.data[r].id;

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

var post = [];

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
        data['title'] = post.post_name;
        data['description'] = post.post_content;
        data['start_time'] = post.post_time;
        data['start_date'] = post.post_date;
        data['cover_id'] = photoId;
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

    if (msg.search('finish') == 0) {
        page.open("http://78.27.137.156:8888/api/publish/" + post.id, function(status) {
            setTimeout(function() {
                phantom.exit();
            }, 0);
            phantom.onError = function(){};
        });
    }

    if (msg.search('exit') == 0) {
        setTimeout(function() {
            phantom.exit();
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

page.open("http://78.27.137.156:8888/api/get-publications", function(status) {

    const TEXT_PREFIX = /<html><head><\/head><body><pre style="word-wrap: break-word; white-space: pre-wrap;">/;
    const TEXT_SUFFIX = /<\/pre><\/body><\/html>/;

    var content = page.content;

    if (TEXT_PREFIX.test(content) && TEXT_SUFFIX.test(content)) {
        content = content.replace(TEXT_PREFIX, '').replace(TEXT_SUFFIX, '');
    }



    post = JSON.parse(content);

    if (post.length == 0) {
        setTimeout(function() {
            phantom.exit();
        }, 100);
    }

    console.log('Content: ' +  JSON.stringify(post, undefined, 4));
    console.log(status);

    page.open("http://www.facebook.com/login.php");
});
//page.open("http://www.facebook.com/login.php");

setTimeout(function() {
    phantom.exit();
}, 300000);