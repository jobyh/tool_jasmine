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
* @copyright 2017 onwards Totara Learning Solutions Ltd
* @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
* @author    Joby Harding <joby.harding@totaralearning.com>
* @package   tool_jasmine
*/

namespace tool_jasmine\output;

defined('MOODLE_INTERNAL') || die();

class renderer extends \plugin_renderer_base {

    public function render_behat_feature(\templatable $feature) {
        return $this->render_from_template('tool_jasmine/behat_feature', $feature->export_for_template($this));
    }

}