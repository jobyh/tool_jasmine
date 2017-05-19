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

use \tool_jasmine\spec_finder;

class spec_finder_testcase extends basic_testcase {

    protected static $dummy_plugin_dir;

    public static function setUpBeforeClass() {
        global $CFG;
        parent::setUpBeforeClass();
        self::$dummy_plugin_dir = "{$CFG->dirroot}/admin/tool/jasmine/tests/fixtures/dummy_plugin";
    }

    public function test_file_is_spec() {
        $suffix = spec_finder::SPEC_SUFFIX;

        $paths = [
            "foo{$suffix}"  => true,
            'foobar.js.php' => false,
            'foo_spec.php'  => false,
            'foo_bar.js.php'=> false,
            $suffix         => false,
        ];

        foreach ($paths as $path => $expected) {
            $message  = "Expected path '{$path}' ";
            $message .= ($expected === true) ? 'to ' : 'not to ';
            $message .= 'pass spec_finder::path_is_spec()';
            $this->assertEquals($expected, spec_finder::filename_is_spec($path), $message);
        }
    }

    public function test_find_in_directory_throws() {
        $this->setExpectedException('\coding_exception');
        $dir = implode('/', [self::$dummy_plugin_dir, 'not_a_dir']);
        spec_finder::find_in_directory($dir);
    }

    public function test_find_in_directory() {
        $dir = implode('/', [self::$dummy_plugin_dir, spec_finder::PLUGIN_SPEC_DIR]);
        $expected = ['dummy1', 'dummy2', 'dummy3'];
        $actual = spec_finder::find_in_directory($dir);

        $this->assertEquals($expected, $actual);
    }

    public function test_find_in_plugins() {

        // We can only check for the specs we can be
        // sure exist (those included with tool_jasmine).
        $expected = ['example'];
        $actual = spec_finder::find_in_plugins()['tool_jasmine'];

        $this->assertEquals($expected, $actual);
    }

    public function test_find_in_group_dirs_throws() {
        $this->setExpectedException('\coding_exception');
        $dir = implode('/', [self::$dummy_plugin_dir, 'not_a_dir']);
        spec_finder::find_in_group_dirs($dir);
    }

    public function test_find_in_group_dirs() {
        $dir = implode('/', [self::$dummy_plugin_dir, spec_finder::PLUGIN_SPEC_DIR]);
        $expected = [
            'flumps' => ['posie', 'pootle', 'perkin'],
            'local_foo' => ['bar'],
        ];
        $actual = spec_finder::find_in_group_dirs($dir);
        
        // This is covered by then next assertion but just make it
        // explicit: There is a 'no_specs' directory containing a file
        // which does not end in the spec suffix so should not be added.
        $this->assertArrayNotHasKey('no_specs', $actual);

        $expectedsorted = sort($expected['flumps']);
        $actualsorted = sort($actual['flumps']);
        $this->assertEquals($expectedsorted, $actualsorted);

        // No need to sort this one as just a single entry.
        $this->assertEquals($expected['local_foo'], $actual['local_foo']);
    }

}