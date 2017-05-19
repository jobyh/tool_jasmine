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

if (file_exists($paths->features)) {
    // TODO make this more robust and remove diretories too
    // if found even though we only expect this directory to
    // contain feature files.
    $entries = @scandir($paths->features);
    if (is_array($entries)) {
        foreach($entries as $entry) {
            unlink("{$paths->features}/{$entry}");
        }
    }
    rmdir($paths->features);
}

mkdir($paths->features);
testing_fix_file_permissions($paths->features);

$pluginspecs = \tool_jasmine\spec_finder::find_in_plugins();
$toolspecs = \tool_jasmine\spec_finder::find_in_group_dirs($CFG->dirroot . '/admin/tool/jasmine/' .
\tool_jasmine\spec_finder::PLUGIN_SPEC_DIR);
$allspecs = array_merge($pluginspecs, $toolspecs);

$renderer = $PAGE->get_renderer('tool_jasmine');

foreach($allspecs as $featurename => $specs) {
    $content = $renderer->render(new \tool_jasmine\output\behat_feature($featurename, $specs));
    $outputpath = implode('/', array($paths->features, "{$featurename}.feature"));
    file_put_contents($outputpath, $content);
    testing_fix_file_permissions($outputpath);
}

exit(0);