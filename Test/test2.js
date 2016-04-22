
var page = require('webpage').create();
page.settings.userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36';
var system = require('system');

page.onInitialized = function() {
    page.onCallback = function(data) {
        page.injectJs('helper.js');
        var currentUrl = page.evaluate(function() {
            return window.location.href;
        });
        console.log('Main page is loaded and ready: ' + currentUrl);
        var result = page.evaluate(function() {






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








        });

        console.log(result);
        phantom.exit();
        /**
         * Do Login
         */
        if (currentUrl == 'https://www.facebook.com/login.php') {
            console.log('Do login...');
            page.evaluate(function() {
                actions.login();
            });
            console.log('Done.');
        }

        if (currentUrl == 'https://www.facebook.com/') {
            console.log('Go to events...');
            page.open("https://www.facebook.com/groups/1346688292014965/events/");
            console.log('Done');
        }

        if (currentUrl == 'https://www.facebook.com/groups/1346688292014965/events/') {
            //api.pressCreateEventButton();
        }

    };

    page.evaluate(function() {
        document.addEventListener('DOMContentLoaded', function() {
            window.callPhantom();
        }, false);
        console.log("Added listener to wait for page ready");
    });

};

page.open("http://www.facebook.com/login.php");
