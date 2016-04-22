var eventManager = new EventManager();
eventManager.on('event.page_loaded', function() {
    /**
     * On Login page
     */
    if (window.location.href == 'https://www.facebook.com/login.php') {
        actions.login();
    }

    /**
     * On home page
     */
    if (window.location.href == 'https://www.facebook.com/') {
        window.location.href = 'https://www.facebook.com/groups/1346688292014965/events/';
    }

    if (window.location.href == 'https://www.facebook.com/groups/1346688292014965/events/') {
        var self = this;
        self.fire('event.page_group_events_loaded');
    }

});

/**
 * On Group events
 */
eventManager.on('event.page_group_events_loaded', function() {

    setTimeout(function() {
        //helper.getByClass('_4jy1', 0).click();
        helper.getByAttr('data-testid=event-create-button', 0).click();

        waitFor.pageClass('uiOverlayButton', 0, function() {
            eventManager.fire('event.page_group_events_popup_loaded');
        });
    }, 5000);

});

/**
 * On Group events popup opened
 */
eventManager.on('event.page_group_events_popup_loaded', function() {
    console.log('keypress');

    setTimeout(function() {
        console.log('upload');
        console.log('CLICKED');
        helper.getByClass('uiOverlayButton', 1).click();
    }, 5000);

});

eventManager.fire('event.page_loaded');

