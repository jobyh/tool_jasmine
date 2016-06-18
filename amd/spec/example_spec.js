/* jshint jasmine: true */
require(['tool_jasmine/example'], function(example) {

    describe('example module', function() {

        describe('addOne', function() {

            it('Should add one to a given number', function() {
                expect(example.addOneTo(2)).toEqual(3);
            });

            it('Should throw an Error if param is not a number', function() {
                expect(example.addOneTo.bind(null, 'foob')).toThrow();
            });

        });

        describe('sayHiTo', function() {

            it('Should greet a given person', function() {
                expect(example.sayHiTo('Joby')).toEqual('Hi Joby!');
            });

            it('Should append a custom message after greeting', function() {
                expect(example.sayHiTo('Joby', 'you rascal!')).toEqual('Hi Joby, you rascal!');
            });

        });

    });

    specDone();

});
