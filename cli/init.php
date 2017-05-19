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

// No web access.
isset($_SERVER['REMOTE_ADDR']) && die();

global $CFG, $PAGE;

define('CLI_SCRIPT', true);

require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');

$paths = new \tool_jasmine\fs_paths();

if (!is_writable($paths->root)) {
    $dataroot = \tool_jasmine\fs_paths::CFG_PROP_DATAROOT;
    throw new coding_exception("\$CFG property {$dataroot} does not exist or is not writable");
}

if (!file_exists($paths->features)) {
    mkdir($paths->features);
}

// Find specs and generate behat features under the tool_jasmine dataroot directory.
$specsdata = \tool_jasmine\spec_finder::find_in_plugins();
$renderer = $PAGE->get_renderer('tool_jasmine');

foreach($specsdata as $frankenstyle => $specs) {
    $content = $renderer->render(new \tool_jasmine\output\behat_feature($frankenstyle, $specs));
    $outputpath = implode('/', array($paths->features, "{$frankenstyle}.feature"));
    file_put_contents($outputpath, $content);
    testing_fix_file_permissions($outputpath);
}

exit(0);