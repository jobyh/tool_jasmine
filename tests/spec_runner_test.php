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

defined('MOODLE_INTERNAL') || die();

use \tool_jasmine\spec_runner;

class spec_runner_testcase extends basic_testcase {

    public function test_it_can_be_instantiated() {
        $expected = 'tool_jasmine\spec_runner';
        $this->assertEquals($expected, get_class(new spec_runner()));
    }

    public function test_it_outputs_a_string() {
        $this->assertTrue(is_string((new spec_runner())->out()));
    }

    public function test_it_adds_specs() {
        $spec1 = 'Specs are just strings of JS';
        $spec2 = 'What larks Pip';
        $output = (new spec_runner())
            ->spec($spec1)
            ->spec($spec2)
            ->out();
        $this->assertRegExp("/{$spec1}/", $output);
        $this->assertRegExp("/{$spec2}/", $output);
    }

    public function test_it_adds_jasmine_js_assets() {
        $output = (new spec_runner())->out();
        $jsfiles = array(
            'boot.js',
            'jasmine.js',
            'jasmine-html.js',
        );

        foreach ($jsfiles as $jsfile) {
            $url = (new moodle_url("/admin/tool/jasmine/lib/jasmine-2.5.2/{$jsfile}"))->out();
            $tag = html_writer::tag('script', '', array('type' => 'text/javascript', 'src' => $url));
            $regex = '/' . preg_quote($tag, '/') . '/';
            $this->assertRegExp($regex, $output);
        }
    }

    public function test_it_adds_jasmine_css() {
        $output = (new spec_runner())->out();

        $url = (new moodle_url("/admin/tool/jasmine/lib/jasmine-2.5.2/jasmine.css"))->out();
        $tag = html_writer::tag('link', '', array('type' => 'text/css', 'rel' => 'stylesheet', 'href' => $url));
        $regex = '/' . preg_quote($tag, '/') . '/';
        $this->assertRegExp($regex, $output);
    }

    public function test_it_adds_sinon_js() {
        $output = (new spec_runner())->out();

        $url = (new moodle_url("/admin/tool/jasmine/lib/sinon-2.1.0/sinon-2.1.0.js"))->out();
        $tag = html_writer::tag('script', '', array('type' => 'text/javascript', 'src' => $url));
        $regex = '/' . preg_quote($tag, '/') . '/';
        $this->assertRegExp($regex, $output);
    }

}