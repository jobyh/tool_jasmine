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

use tool_jasmine\spec_runner_generator;

class spec_runner_generator_testcase extends basic_testcase {

    public function test_it_can_be_initialised() {

        $expected = 'tool_jasmine\spec_runner_generator';
        $actual = get_class(new spec_runner_generator());

        $this->assertEquals($expected, $actual);

    }

    public function test_it_outputs_html() {

        $output = (new spec_runner_generator())->out();

        $expected = true;
        $actual = preg_match('/<!DOCTYPE html>/', $output) === 1;

        $this->assertEquals($expected, $actual);

    }

    public function test_it_outputs_spec_script_tags() {

        global $CFG;

        $dir = "admin/tool/jasmine/tests/fixtures/amd_dir_with_specs";
        $specfiles = array('module1_spec.js', 'module2_spec.js');

        $output = (new spec_runner_generator())
            ->add_dir("{$CFG->dirroot}/$dir")
            ->out();

        $expected = true;

        foreach ($specfiles as $specfile) {
            $fileurl = (new moodle_url("/{$dir}/spec/{$specfile}"))->out();
            $fileurlescaped = str_replace('/', '\/', $fileurl);

            $actual = preg_match("/<script src=\"{$fileurlescaped}\"><\/script>/", $output) === 1;
            $message = "Missing script tag for {$fileurl}";

            $this->assertEquals($expected, $actual, $message);
        }

    }

    public function test_it_gets_renderable() {

        $expected = 'tool_jasmine\output\spec_runner';
        $actual = get_class((new spec_runner_generator())->get_renderable());

        $this->assertEquals($expected, $actual);

    }

    public function test_it_throws_if_added_dir_doesnt_exist() {

        $this->setExpectedException('coding_exception');

        (new spec_runner_generator())->add_dir('/tmp/___dir/doesnt/__XXXxx___/exist');

    }

}
