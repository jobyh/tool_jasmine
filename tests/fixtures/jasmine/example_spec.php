<?php

require('../../../../../../config.php');

\tool_jasmine\boilerplate::check_permissions();
\tool_jasmine\boilerplate::init_page(new moodle_url('/admin/tool/jasmine/tests/fixtures/jasmine/example_spec.php'));

$spec = <<<JS

    describe('Test an AMD module', function() {
        it('can do async', function(done) {
            require(['jquery'], function($) {
                expect(typeof $).toBe('function');
                done();
            })
        });
    });

JS;

echo (new \tool_jasmine\spec_runner())
    ->spec($spec)
    ->out();
