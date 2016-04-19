'use strict';

function sleep(miliseconds) {
    var currentTime = new Date().getTime();

    console.log('sleep called');

    while (currentTime + miliseconds >= new Date().getTime()) {
    }
}

var sys = require('system');
var page = require('webpage').create();
//var siteUrl = 'http://78.27.137.156:8888/?_url=/index/debug';
var siteUrl = 'https://www.facebook.com/';

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

page.addCookie({
    'name': 'Cookie',
    'value': 'datr=Fqq5VoJH8N-lNGC9hLS_lSNr; lu=ghfpoansgRQyV-XAERRl2ruw; a11y=%7B%22sr%22%3A0%2C%22sr-ts%22%3A1460710215500%2C%22jk%22%3A0%2C%22jk-ts%22%3A1460710215500%2C%22kb%22%3A0%2C%22kb-ts%22%3A1460974265211%2C%22hcm%22%3A0%2C%22hcm-ts%22%3A1460710215500%7D; c_user=100000734997222; fr=0T3PGWKWVc3yFSSmh.AWXPjJQ1ne7rBm2w4e1QknUDsiE.BWuave.dR.FcM.0.AWU33A3g; xs=36%3AA1AeSU4-xeOoNg%3A2%3A1455008848%3A4915; csm=2; s=Aa6netrhbgsoR25u.BXELQY; sb=0U8LV_D2U8dSKM7vw38A6B62; p=-2; act=1460987199354%2F8; presence=EDvF3EtimeF1460987215EuserFA21B00734997222A2EstateFDt2F_5bDiFA2thread_3a1604326793123335A2EsiFA21604326793123335A2ErF1C_5dElm2FnullEuct2F1460970994300EtrFA2loadA2EtwF400526530EatF1460987199203G460987215123CEchFDp_5f1B00734997222F4CC; wd=1858x437',
    'domain': 'facebook.com'
});

page.open(siteUrl, function(status) {
    page.injectJs('//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js');

    if (status === 'success') {
        // Выполняем весь CSS, JS код на странице
        var Links = page.evaluate(function() {
            
            var x = document.getElementsByClassName("jewelButton");
            var i;
            for (i = 0; i < 1; i++) {
                //x[i].style.backgroundColor = "red";
                x[i].click();
            }

            return [];
        });

        console.log('sleep');
        sleep(5000);

        page.render('test.png');
        // Вывод результата через обычный console.log
        console.log(JSON.stringify(Links));
    }

    exit();
});

