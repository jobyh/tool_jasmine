<?php
// This file is part of tool_jasmine
//
// Copyright (C) 2017 onwards Totara Learning Ltd
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
 * @copyright 2017 onwards Totara Learning Ltd
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Joby Harding <joby@totaralearning.com>
 * @package   tool_jasmine
 */

defined('MOODLE_INTERNAL') || die();

use tool_jasmine\output\renderer;
use tool_jasmine\output\behat_feature;

class renderer_testcase extends basic_testcase {

    public function test_it_can_be_initialised() {
        $expected = 'tool_jasmine\output\renderer';
        $actual = get_class(new renderer(new moodle_page(), RENDERER_TARGET_GENERAL));

        $this->assertEquals($expected, $actual);
    }

    public function test_render_behat_feature() {
        global $PAGE;

        $specs = ['foo', 'bar', 'baz'];
        $numspecs = count($specs);
        $renderer = $PAGE->get_renderer('tool_jasmine', null, RENDERER_TARGET_GENERAL);
        $renderable = new behat_feature('local_foo', $specs);

        $rendered = $renderer->render($renderable);

        $this->assertStringStartsWith('@local_foo @javascript @jasmine', $rendered);

        // There
//        $this->assert
//        $this->assertStringCon('/^@Feature: local_foo JavaScript$/', $rendered);
//
//        $this->assertRegExp("/(And I navigate to^(spec has passed)){{$numspecs}}/", $rendered);
    }

}