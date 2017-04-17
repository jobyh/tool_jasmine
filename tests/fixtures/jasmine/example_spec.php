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

require('../../../../../../config.php');

\tool_jasmine\boilerplate::check_acceptance_site();
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

echo (new \tool_jasmine\spec_runner())->spec($spec)->out();
