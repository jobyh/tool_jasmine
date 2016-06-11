<?php
/*
 * Copyright (C) 2016 onwards Joby Harding
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright 2016 onwards Joby Harding
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Joby Harding <joby@iamjoby.com>
 * @package   tool_jasmine
 */

namespace tool_jasmine;

class spec_runner_generator {

    const FILENAME_SPECRUNNER = 'spec_runner.php';

    const DIRNAME_SPEC = 'spec';

    protected $renderable;

    protected $specfiles;

    public function __construct() {

        $this->renderable = new output\spec_runner();
        $this->renderable->basedir = 'admin/tool/jasmine';

    }

    public function get_renderable() {

        return $this->renderable;

    }

    /**
     * Output the spec runner html.
     */
    public function out() {

        global $PAGE;

        return $PAGE->get_renderer('tool_jasmine')->render($this->renderable);

    }

    /**
     * Return a list of JavaScript files in a given directory.
     *
     * @param string $dirpath An absolute path to the directory.
     * @return array
     */
    protected function get_js_files($dirpath) {

        $listing = @scandir($dirpath);
        
        if ($listing === false) {
            $message = "Could not scan directory {$dirpath}";
            throw new \coding_exception($message);
        }

        return array_filter($listing, function($dir) {
            return preg_match('/\.js$/', $dir) === 1;
        });

    }

    /**
     * List spec files.
     *
     * @param string $dirpath
     * @return array|null
     */
    public function get_spec_files($dirpath) {

        if ($this->specfiles === null) {
            $this->specfiles = $this->get_js_files("{$dirpath}/spec");
        }

        return $this->specfiles;

    }

    // TODO combine this with get_spec_files so not iterating multiple times.
    public function get_spec_urls($dirpath) {

        global $CFG;

        $relativebase = str_replace($CFG->dirroot, '', $dirpath);

        return array_map(function($sourcefile) use ($dirpath, $relativebase) {
            return (new \moodle_url("{$relativebase}/spec/{$sourcefile}"))->out();
        }, $this->get_spec_files($dirpath));

    }

    /**
     * Add a directory which should be scanned for specs and source files.
     *
     * @param string $dirpath
     * @return spec_runner_generator $this
     */
    public function add_dir($dirpath) {

        $this->renderable->specfiles = array_merge(
            $this->renderable->specfiles,
            $this->get_spec_urls($dirpath)
        );

        return $this;

    }

    /**
     * Set whether authentication is required to view spec runners.
     *
     * @param bool $requireauth
     * @return spec_runner_generator $this
     */
    public function set_auth($requireauth = true) {

        $this->renderable->requireauth = $requireauth;

        return $this;

    }

    /**
     * Add arbitrary JavaScript to be rendered.
     *
     * @param string $javascript A string of JavaScript.
     * @return spec_runner_generator $this
     */
    public function custom_js($javascript) {

        $this->renderable->customjs = $javascript;

        return $this;

    }

}
