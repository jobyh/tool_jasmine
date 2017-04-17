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

/**
 * Class boilerplate
 *
 * Class to take care of all that duplicated boilerplate
 * such as permissions checks and setting current URL etc.
 *
 * This class encapsulates much of the procedual boilerplate
 * which uses globals required on every page.
 *
 * @package tool_jasmine
 */
class boilerplate {

    /**
     * Check user has permission to view the spec runner.
     */
    public static function check_permissions() {
        require_login();
        require_capability('moodle/site:config', \context_system::instance());
    }

    /**
     * Generates the
     *
     * No typehinting so we can test this.
     *
     * @param $output
     * @param $heading
     * @return string
     */
    public static function page_header() {
        global $OUTPUT;
        return $OUTPUT->header();
    }


    public static function init_page($url) {
        global $PAGE;

        $PAGE->set_url($url);
        $PAGE->set_context(\context_system::instance());
        $PAGE->set_title('Jasmine JavaScript spec');

        // TODO
        //$page->set_pagelayout('noblocks');
    }

    /**
     * @return string
     */
    public static function page_footer() {
        global $OUTPUT;
        return $OUTPUT->footer();
    }

    /**
     * Return a <style> element which imports a stylesheet based on current theme name.
     *
     * @return string
     */
    public static function theme_styles() {
        global $PAGE;
        $sheeturl = new \moodle_url("/admin/tool/jasmine/styles/{$PAGE->theme->name}.css");
        return \html_writer::tag('style', "@import \"{$sheeturl}\";");
    }

}