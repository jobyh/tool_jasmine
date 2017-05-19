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
 * @author    Joby Harding <joby.harding@totaralearning.com>
 * @package   tool_jasmine
 */

defined('MOODLE_INTERNAL') || die();

use tool_jasmine\fs_paths;

/**
 * Class fs_manager_testcase
 *
 * Filesystem manager.
 */
class fs_paths_testcase extends basic_testcase {

    public function test_initialisation_missing_dataroot_throws() {
        $this->setExpectedException('coding_exception');

        // The tool_jasmine_dataroot property is required.
        // When no config object is passed the global $CFG
        // will be used.
        new fs_paths();
    }

    public function test_initialisation_trailing_slash_throws() {
        $this->setExpectedException('coding_exception');

        $cfg = (object)array(fs_paths::CFG_PROP_DATAROOT => '/what/larks/pip/');
        new fs_paths($cfg);
    }

    public function test_it_can_be_initialised() {
        $cfg = (object)array(
            fs_paths::CFG_PROP_DATAROOT => '/foo/bar/baz',
        );

        $expected = 'tool_jasmine\fs_paths';
        $actual = get_class(new fs_paths($cfg));
        $this->assertEquals($expected, $actual);
    }

    public function test_props() {
        $cfg = (object)array(fs_paths::CFG_PROP_DATAROOT => '/what/larks/pip');
        $paths = new fs_paths($cfg);

        $this->assertEquals('/what/larks/pip', $paths->root);
        $this->assertEquals('/what/larks/pip/features', $paths->features);
    }

}