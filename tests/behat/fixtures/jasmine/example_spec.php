<?php
// This file is part of tool_jasmine
//
// Copyright (C) 2017 onwards Joby Harding
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @copyright 2017 onwards Joby Harding
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Joby Harding <joby@iamjoby.com>
 * @package   tool_jasmine
 */

require('../../../../../../../config.php');

$spec = <<<JS

describe("tool_jasmine is proper good because", function() {

    it('can test platform globals', function() {
        expect(typeof M.cfg).toBe('object');
    });

    it('can test AMD modules', function(done) {
        require(['jquery'], function($) {
            expect(typeof $).toBe('function');
            done();
        });
    });
    
    it('can test methods which are async', function(done) {
        require(['core/str'], function(str) {
            str.get_string('block', '', null, 'en').then(function(localised) {
                expect(localised).toBe('Block');
                done();
            });
        });
    });

    it('can test YUI modules', function(done) {
        expect(typeof M.core.init_formautosubmit).toBe('undefined');
        Y.use('moodle-core-formautosubmit', function(Y) {
            expect(typeof M.core.init_formautosubmit).toBe('function');
            done();
        });
    });

    // Note that the JS under test here is included in the call below to
    // generate the runner passed in the 'scripts' option item.
    it('can test crappy old JavaScript in the global namespace', function() {
        expect(mrCrufty()).toBe("still chuggin' away");
    });

    it("can test crazy freakin' spaghetti mess!", function(done) {
        require(['jquery'], function($) {
            Y.use('node', function(Y) {
                var fragment = Y.Node.create('<div class="root"><div class="child"></div></div>');
                var message = (M.cfg.developerdebug === true) ? 'Debugging ON' : 'Debugging OFF';
                fragment = $(fragment.getDOMNode()).find('.child').html(message).end().get(0);
                
                // Specs can only be viewed with debugging on.
                expect(fragment.querySelector('.child').innerHTML).toBe('Debugging ON');
                done();
            });
        });
    });
    
});

JS;

$url = '/admin/tool/jasmine/tests/behat/fixtures/jasmine/example_spec.php';

echo \tool_jasmine\spec_runner::generate($url, $spec, [
    // The scripts option adds Javascript by loading a URL using $PAGE->requires->js().
    'scripts' => ['/admin/tool/jasmine/js/jurassic.js']
]);