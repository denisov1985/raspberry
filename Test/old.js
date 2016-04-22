/**
 * Created by Dmytro_Denysov on 4/19/2016.
 */
var result = page.evaluate(function() {
    var x = document.getElementsByClassName("_4jy1");
    helper.getByClass("_4jy1", 0).click();
});
window.setTimeout(function() {

    var result = page.evaluate(function() {
        var x = document.getElementsByClassName("uiOverlayButton");
        var i;
        for (i = 0; i < 2; i++) {
            if (i == 1) {
                x[i].click();
                return typeof x[i];
            }
        }

        return [];
    });
    console.log(result);
    window.setTimeout(function() {
        page.render("../Public/page.png");
        phantom.exit();
    }, 20000);
}, 20000);