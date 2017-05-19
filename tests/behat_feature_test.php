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

use tool_jasmine\output\behat_feature;
use tool_jasmine\spec_finder;

class behat_feature_testcase extends basic_testcase {

    public function test_initialisation() {
        $expected = 'tool_jasmine\output\behat_feature';
        $actual = get_class(new behat_feature('tool_jasmine', []));
        $this->assertEquals($expected, $actual);
    }

    public function test_properties() {
        global $CFG;

        $pluginspecdir = spec_finder::PLUGIN_SPEC_DIR;

        $specs = spec_finder::find_in_directory("{$CFG->dirroot}/admin/tool/jasmine/{$pluginspecdir}");
        $instance = new behat_feature('tool_jasmine', $specs);

        $this->assertEquals('tool_jasmine', $instance->plugin);
        $this->assertEquals(['example'], $instance->specs);
    }

}