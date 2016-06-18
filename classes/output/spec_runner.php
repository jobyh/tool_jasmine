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

namespace tool_jasmine\output;

defined('MOODLE_INTERNAL') || die();

class spec_runner implements \renderable, \templatable {

    /**
     * @var bool
     */
    public $requireauth = true;

    /**
     * @var string
     */
    public $customjs = '';

    /**
     * @var array
     */
    public $specfiles = array();

    public function get_requirecode() {

        global $CFG;

        $output = '';

        // No caching.
        $jsrev = '-1';

        $jsloader = new \moodle_url($CFG->httpswwwroot . '/lib/javascript.php');
        $jsloader->set_slashargument('/' . $jsrev . '/');
        $requirejsloader = new \moodle_url($CFG->httpswwwroot . '/lib/requirejs.php');
        $requirejsloader->set_slashargument('/' . $jsrev . '/');

        $requirejsconfig = file_get_contents($CFG->dirroot . '/lib/requirejs/moodle-config.js');

        // No extension required unless slash args is disabled.
        $jsextension = '.js';
        if (!empty($CFG->slasharguments)) {
            $jsextension = '';
        }

        $requirejsconfig = str_replace('[BASEURL]', $requirejsloader, $requirejsconfig);
        $requirejsconfig = str_replace('[JSURL]', $jsloader, $requirejsconfig);
        $requirejsconfig = str_replace('[JSEXT]', $jsextension, $requirejsconfig);

        $output .= \html_writer::script($requirejsconfig);

        if ($CFG->debugdeveloper) {
            $output .= \html_writer::script('', $this->js_fix_url('/lib/requirejs/require.js'));
        } else {
            $output .= \html_writer::script('', $this->js_fix_url('/lib/requirejs/require.min.js'));
        }

        return $output;

    }

    /**
     * Returns the actual url through which a script is served.
     *
     * Note: This method is copied from TODO
     * As that method is not accessible we must duplicate code.
     *
     * @param \moodle_url|string $url full moodle url, or shortened path to script
     * @return \moodle_url
     */
    public function js_fix_url($url) {

        global $CFG;

        if ($url instanceof \moodle_url) {
            return $url;
        }

        if (strpos($url, '/') === 0) {

            // Fix the admin links if needed.
            if ($CFG->admin !== 'admin') {
                if (strpos($url, "/admin/") === 0) {
                    $url = preg_replace("|^/admin/|", "/$CFG->admin/", $url);
                }
            }

            if (debugging()) {
                // Check file existence only when in debug mode.
                if (!file_exists($CFG->dirroot . strtok($url, '?'))) {
                    throw new \coding_exception('Attempt to require a JavaScript file that does not exist.', $url);
                }
            }

            if (substr($url, -3) === '.js') {
                $jsrev = '-1';
                if (empty($CFG->slasharguments)) {
                    return new \moodle_url($CFG->httpswwwroot.'/lib/javascript.php', array('rev' => $jsrev, 'jsfile' => $url));
                } else {
                    $returnurl = new \moodle_url($CFG->httpswwwroot.'/lib/javascript.php');
                    $returnurl->set_slashargument('/'.$jsrev.$url);
                    return $returnurl;
                }
            } else {
                return new \moodle_url($CFG->httpswwwroot.$url);
            }
        }

        throw new \coding_exception('Invalid JS url, it has to be shortened url starting with / or moodle_url instance.', $url);
    }

    /**
     * Wrap get_head_code so it is only called once.
     *
     * get_head_code was only designed to be called once
     * for a given page and so throws an error if called
     * more than that in a thread of execution. As we're
     * generating multiple spec files we need to use its
     * output for each.
     *
     * @return null|string
     */
    public function get_headcode() {

        global $PAGE;

        static $headcode = null;

        if ($headcode === null) {
            $headcode = $PAGE->requires->get_head_code(
                $PAGE, new \core_renderer($PAGE, null)
            );
        }

        return $headcode;
    }

    /**
     * Implements export_for_template().
     *
     * @param \renderer_base $output
     * @return array
     */
    public function export_for_template(\renderer_base $output) {

        global $CFG, $PAGE;

        return array(
            'requireauth' => $this->requireauth,
            'wwwroot' => $CFG->wwwroot,
            'dirroot' => $CFG->dirroot,
            'speccount' => count($this->specfiles),
            'specfiles' => $this->specfiles,
            'requirecode' => $this->get_requirecode(),
            'headcode' => $this->get_headcode(),
            'topbodycode' => $PAGE->requires->get_top_of_body_code(),
            'endcode' => $PAGE->requires->get_end_code(),
            'customjs' => $this->customjs,
        );

    }

}
