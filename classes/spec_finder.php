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

namespace tool_jasmine;

defined('MOODLE_INTERNAL') || die();

class spec_finder {

    /*
     * Standard spec location inside plugins.
     */
    const PLUGIN_SPEC_DIR = 'tests/behat/fixtures/tool_jasmine';

    /*
     * All tool_jasmine specs must use this suffix.
     */
    const SPEC_SUFFIX = '_spec.js.php';

    /**
     * Does the given filename end with the spec suffix?
     *
     * @param $filename string
     * @return boolean
     */
    public static function filename_is_spec($filename) {
        $pattern = '/\w+' . preg_quote(self::SPEC_SUFFIX) . '/';
        return (preg_match($pattern, $filename) === 1) ? true : false;
    }

    /**
     * Return an array of spec files found in a given directory.
     *
     * @param $dir string The directory to search. Non-recursive.
     * @return string[]
     */
    public static function find_in_directory($dir) {

        $entries = @scandir($dir);

        if ($entries === false) {
            throw new \coding_exception("'{$dir}' is not a directory.");
        }

        $specfiles = array_filter($entries, function($entry) use ($dir) {
            return is_file("{$dir}/$entry") && self::filename_is_spec($entry);
        });

        // Ensure array keys are reset.
        return array_values($specfiles);
    }

    /**
     * Return an array of all plugin directories (absolute paths) keyed by plugin name.
     *
     * @return string[]
     */
    protected static function list_plugin_dirs() {

        $types = array_keys(\core_component::get_plugin_types());

        return array_reduce($types, function($acc, $type) {
            $plugins = array_values(\core_component::get_plugin_list($type));
            return array_merge($acc, $plugins);
        }, []);
    }

    /**
     * Return an array of Jasmine specs found in entire system.
     *
     * Array contains absolute paths to each spec.
     *
     * @return array
     */
    public static function find_in_system() {
        return array_reduce(self::list_plugin_dirs(), function($acc, $plugindir) {
            $specdir = $plugindir . '/' . self::PLUGIN_SPEC_DIR;
            $specs = is_dir($specdir) ? self::find_in_directory($specdir) : [];
            $fullpaths = array_map(function($spec) use ($specdir) {
                return $specdir . '/' . $spec;
            }, $specs);
            return array_merge($acc, $fullpaths);
        }, []);
    }

//    /**
//     * Return an array of Jasmine specs found in entire system.
//     *
//     * Array contains absolute paths to each spec.
//     *
//     * @return array
//     */
//    public static function find_in_plugins() {
//        return array_reduce(self::list_plugin_dirs(), function($acc, $plugindir) {
//            $specdir = $plugindir . '/' . self::PLUGIN_SPEC_DIR;
//            $specs = is_dir($specdir) ? self::find_in_directory($specdir) : [];
//            return array_merge($acc, $fullpaths);
//        }, []);
//    }

}