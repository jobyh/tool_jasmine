<?php
// This file is part of tool_jasmine
//
// Copyright (C) 2017 onwards Totara Learning Ltd.
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

namespace tool_jasmine;

defined('MOODLE_INTERNAL') || die();

/**
 * Class fs_paths
 *
 * Filesystem paths.
 *
 * @package tool_jasmine
 */
class fs_paths {

    const CFG_PROP_DATAROOT = 'tool_jasmine_dataroot';

    const FEATURES_DIR = 'features';

    /**
     * @var string
     */
    public $root;

    /**
     * @var string
     */
    public $features;

    /**
     * fs_manager constructor.
     *
     * @param \stdClass $cfg Optional. Allow object to be injected for testing / loosen coupling.
     */
    public function __construct($cfg = null) {
        global $CFG;

        $cfg = ($cfg === null) ? $CFG : $cfg;

        $datarootprop = self::CFG_PROP_DATAROOT;

        if (property_exists($cfg, $datarootprop) === false) {
            $message = "Required config property '{$datarootprop}' is missing.";
            throw new \coding_exception($message);
        }

        if (substr($cfg->{$datarootprop}, -1) === '/') {
            $message = "'{$datarootprop}' must not end with a trailing slash.";
            throw new \coding_exception($message);
        }

        $this->root = $cfg->{$datarootprop};
        $this->features = implode('/', array($this->root, self::FEATURES_DIR));
    }

}