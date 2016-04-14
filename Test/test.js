'use strict';

var sys = require('system');
var page = require('webpage').create();
//var siteUrl = 'http://78.27.137.156:8888/?_url=/index/debug';
var siteUrl = 'http://velokyiv.com/forum/viewforum.php?f=1';

function exit(code) {
    setTimeout(function() {
        phantom.exit(code);
    }, 0);
    phantom.onError = function(){};
}

page.settings.userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36';

page.viewportSize = {
    width: 1280,
    height: 720
};

page.open(siteUrl, function(status) {
    page.injectJs('//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js');
    page.render('../Public/test.png');

    if (status === 'success') {
        // Выполняем весь CSS, JS код на странице
        var Links = page.evaluate(function() {
            var links = $('a');

            console.log(links);

            var hrefs = [];

            /*$.each(links, function(num, item) {
                var href = $(item).attr('href');
                if (href !== '#') {
                    hrefs.push(href);
                }
            });*/

            return links;
        });

        // Вывод результата через обычный console.log
        console.log(JSON.stringify(Links));
    }

    exit();
});