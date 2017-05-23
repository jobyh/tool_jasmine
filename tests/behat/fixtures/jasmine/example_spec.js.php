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

        it('can test AMD modules', function(done) {
            require(['jquery'], function($) {
                expect(typeof $).toBe('function');
                done();
            });
        });
        // ...bok
        
        it('can test methods which are async', function(done) {
            require(['core/str'], function(str) {
                str.get_string('block', '', null, 'en').then(function(localised) {
                    expect(localised).toBe('Block');
                    done();
                });
            });
        });
        // ...bok
    
        it('can test YUI modules', function() {
            expect(typeof M.cfg).toBe('object');
        });
        // ...bok

        it('can test platform globals', function() {
            expect(typeof M.cfg).toBe('object');
        });
        // ...bok

        it('can test crappy old JavaScript in the global namespace', function() {
            expect(mrCrufty()).toBe("still chuggin' away");
        });
        // ...bok
        
        // it("can test crazy freakin' spaghetti-mess :o", function(done) {
        //     require(['jquery'], function($) {
        //         var node = $('<div class="nodey"></div>');
        //         nodey.html(M.cfg.theme);
        //         YUI.add('node', function() {
        //             done();
        //         });
        //     });
        // });
        // ...BGERK!
        
    });

JS;

$url = '/admin/tool/jasmine/tests/behat/fixtures/jasmine/example_spec.js.php';

echo \tool_jasmine\spec_runner::generate($url, $spec, [
    // The scripts option adds Javascript by loading a URL using $PAGE->requires->js().
    'scripts' => ['/admin/tool/jasmine/js/jurassic.js']
]);