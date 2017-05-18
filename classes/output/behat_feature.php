<?php
// This file is part of tool_jasmine
//
// Copyright (C) 2017 onwards Totara Learning Solutions Ltd
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
 * @copyright 2017 onwards Totara Learning Ltd.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Joby Harding <joby.harding@totaralearning.com>
 * @package   tool_jasmine
 */

namespace tool_jasmine\output;

use renderer_base;

defined('MOODLE_INTERNAL') || die();

class behat_feature implements \renderable, \templatable {

    /**
     * The Frankenstyle name of the plugin the feature is associated with.
     *
     * @var string;
     */
    public $plugin = '';


    /**
     * Names of specs to be tested in the Behat feature.
     *
     * @var string[]
     */
    public $specs = [];

    /**
     * behat_feature constructor.
     *
     * @param string $pluginname The Frankenstyle name of the plugin the feature is associated with.
     * @param string[] $specs
     */
    public function __construct($pluginname, $specs) {
        $this->plugin = $pluginname;
        $this->specs = $specs;
    }

    public function export_for_template(renderer_base $output) {
        return get_object_vars($this);
    }

}