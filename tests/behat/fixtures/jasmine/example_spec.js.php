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

    describe('Show some Jasmine test examples', function() {
        
        it('can test globals', function() {
            expect(typeof M.cfg).toBe('object');
        });
        
        // Note YUI loading is async so use Jasmine's done() async support.
        it('can test YUI modules', function() {
            // expect(typeof M.cfg).toBe('object');
            // done();
            YUI().use('moodle-core-notification-alert', function() {
                // Tiny reliance issue here, we don't actually load the error string into JS. It is already loaded by
                // some other code and we just use it,
                new M.core.alert({message: data.error, title: M.util.get_string('error', 'moodle')});
            });
        });
        
        // AMD for 2.9+ only
        if (typeof require !== 'undefined') {
            // AMD is also async.
            it('can test AMD modules', function(done) {
                require(['jquery'], function($) {
                    expect(typeof $).toBe('function');
                    done();
                })
            });
        }
        
    });

JS;

$url = '/admin/tool/jasmine/tests/behat/fixtures/tool_jasmine/example_spec.js.php';
echo \tool_jasmine\spec_runner::generate($url, $spec);
