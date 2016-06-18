require(['jquery', 'tool_jasmine/example2'], function($, example2) {

    describe('example2 module', function() {

        describe('addSuccessNotification', function() {

            it('Should add a notification to a given element', function(done) {
                var dom = $('<div>');
                var message = 'You were successful';

                example2.addSuccessNotification(dom, message).done(function() {
                    expect(dom.find('.alert-success').html()).toEqual(message);
                    done();
                });
            });

        });

    });

    specDone();

});
