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

defined('MOODLE_INTERNAL') || die();

use \tool_jasmine\boilerplate;

class boilerplate_testcase extends advanced_testcase {

    public function test_it_sets_page_url() {
        global $PAGE;

        $this->resetAfterTest();

        $url = new moodle_url('/foo/bar.php');
        boilerplate::init_page($url);

        $this->assertEquals($url, $PAGE->url);
    }

    public function test_theme_styles_requires_theme_sheet() {
        global $PAGE;

        $import = '@import "' . (new moodle_url("/admin/tool/jasmine/styles/{$PAGE->theme->name}.css"))->out() . '";';
        $this->assertRegExp('/' . preg_quote($import, '/') . '/', boilerplate::theme_styles());
    }

}