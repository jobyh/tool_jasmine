<?php
// This file is part of tool_jasmine
//
// Copyright (C) 2016 onwards Joby Harding
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
 * @copyright 2016 onwards Joby Harding
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Joby Harding <joby@iamjoby.com>
 * @package   tool_jasmine
 */

defined('MOODLE_INTERNAL') || die();

use tool_jasmine\output\spec_runner;

class spec_runner_testcase extends advanced_testcase {

    public function test_it_can_be_initialised() {

        $expected = 'tool_jasmine\output\spec_runner';
        $actual = get_class(new spec_runner());

        $this->assertEquals($expected, $actual);

    }

    public function test_get_requirecode_contains_requirejs() {

        $requirecode = (new spec_runner())->get_requirecode();

        $expected = true;
        $actual = (boolean)preg_match('/\/lib\/requirejs\/require\.js/', $requirecode);

        $this->assertEquals($expected, $actual);

    }

    public function test_get_requirecode_contains_minimised_requirejs() {

        global $CFG;

        $this->resetAfterTest();

        $CFG->debugdeveloper = false;

        $requirecode = (new spec_runner())->get_requirecode();

        $expected = true;
        $actual = (boolean)preg_match('/\/lib\/requirejs\/require\.min\.js/', $requirecode);

        $this->assertEquals($expected, $actual);

    }

    public function test_js_fix_url_returns_moodle_url() {

        $expected = 'moodle_url';
        $actual = get_class((new spec_runner())->js_fix_url(new \moodle_url('/index')));

        $this->assertEquals($expected, $actual);

    }

    public function test_js_fix_url_replaces_admin_path() {

        global $CFG;

        $this->resetAfterTest();

        $CFG->developerdebug = true;

        // This is a bit of a 'trick' so that we have
        // a file which exists. Setting $CFG->admin to
        // 'core' in a real deployment would break it.
        $CFG->admin = 'lib';

        $jsfile = "/admin/amd/src/templates.js";

        $expected = $CFG->httpswwwroot . "/lib/javascript.php/-1/{$CFG->admin}/amd/src/templates.js";
        $actual = (new spec_runner())->js_fix_url($jsfile)->out();

        $this->assertEquals($expected, $actual);

    }

    public function test_js_fix_url_throws_when_file_missing() {

        global $CFG;

        $this->resetAfterTest();
        $this->setExpectedException('coding_exception');

        $CFG->developerdebug = true;

        (new spec_runner())->js_fix_url('/file/doesnt/exist_______');

    }

    public function test_js_fix_url_returns_object_when_no_slasharguments() {

        global $CFG;

        $this->resetAfterTest();

        $CFG->slasharguments = null;

        $url = '/admin/tool/jasmine/amd/src/example.js';

        $expected = 'moodle_url';
        $actual = get_class((new spec_runner())->js_fix_url($url));

        $this->assertEquals($expected, $actual);

    }

    public function test_js_fix_url_returns_object_when_not_js_filetype() {

        global $CFG;

        $this->resetAfterTest();

        $CFG->slasharguments = null;

        $url = '/admin/tool/jasmine/renderer.php';

        $expected = 'moodle_url';
        $actual = get_class((new spec_runner())->js_fix_url($url));

        $this->assertEquals($expected, $actual);

    }

    public function test_js_fix_url_throws_when_url_invalid() {

        $this->setExpectedException('coding_exception');

        (new spec_runner())->js_fix_url(7);

    }

}
