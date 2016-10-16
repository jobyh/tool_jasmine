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

// No web access.
isset($_SERVER['REMOTE_ADDR']) && die();

global $CFG;

define('CLI_SCRIPT', true);

require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');
require_once("$CFG->libdir/clilib.php");

echo "Initialising Jasmine spec runners...\n";

$amdmodules = core_requirejs::find_all_amd_modules(true);
$specdirs = array();

foreach ($amdmodules as $amdmodule) {

    $amddir = dirname(dirname($amdmodule));
    $specdir = "{$amddir}/" . \tool_jasmine\spec_runner_generator::DIRNAME_SPEC;
    $specfilepath = "{$amddir}/" . \tool_jasmine\spec_runner_generator::FILENAME_SPECRUNNER;

    // Only once per spec dir.
    if (is_dir($specdir) && !in_array($specdir, $specdirs)) {

        $specdirs[] = $specdir;
        $specfilecontent = (new \tool_jasmine\spec_runner_generator())
            ->add_dir($amddir)
            ->out();

        echo "\tGenerating {$specfilepath}\n";

        file_put_contents($specfilepath, $specfilecontent);
        @chmod($specfilepath, 0755);

    }

}

echo "Jasmine spec runner initialisation complete.\n";
exit(0);
