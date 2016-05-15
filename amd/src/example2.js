define(['jquery', 'core/templates'], function($, templates) {

    return {
        addSuccessNotification: function(domNode, message) {
            return templates.render('core/notification_success', { message: message })
                .done(function(rendered) { $(domNode).append(rendered); })
                .fail(function(err) { throw err; });
        }
    };

});