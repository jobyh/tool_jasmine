define(function() {

    return {
        addOneTo: function(num) {
            if (typeof num !== 'number') {
                throw new Error('addOne expects a number');
            }
            return num + 1;
        },

        sayHiTo: function(name, customMessage) {
            var msg = customMessage === undefined ? '!' : ', ' + customMessage;
            return 'Hi ' + name + msg;
        }

    };

});
