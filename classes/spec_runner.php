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

class spec_runner {

    /**
     * @var array
     */
    protected $specs = array();

    /**
     * Add a spec string for output. Any number may be added. Chainable.
     *
     * @param string $spec
     * @return \tool_jasmine\spec_runner
     */
    public function spec($spec) {
        $this->specs[] = $spec;
        return $this;
    }

    /**
     * Return an HTML string which includes Jasmine's stylesheets.
     *
     * @return string
     */
    protected function jasmine_styles_html() {
        $url = (new \moodle_url("/admin/tool/jasmine/lib/jasmine-2.5.2/jasmine.css"))->out();
        return \html_writer::tag('link', '', array('type' => 'text/css', 'rel' => 'stylesheet', 'href' => $url));
    }

    /**
     * Return an HTML string which includes Jasmine's Javascript files.
     *
     * @return string
     */
    protected function jasmine_scripts_html() {
        // Order of scripts is important.
        return array_reduce(array('jasmine', 'jasmine-html', 'boot'), function($carry, $item) {
            $url = (new \moodle_url("/admin/tool/jasmine/lib/jasmine-2.5.2/{$item}.js"))->out();
            return $carry .= \html_writer::tag('script', '', array('type' => 'text/javascript', 'src' => $url));
        }, '');
    }

    /**
     * Return an HTML string to include the Sinon library.
     *
     * @return string
     */
    protected function sinon_scripts_html() {
        $url = (new \moodle_url("/admin/tool/jasmine/lib/sinon-2.1.0/sinon-2.1.0.js"))->out();
        return \html_writer::tag('script', '', array('type' => 'text/javascript', 'src' => $url));
    }

    /**
     * Generate HTML to display the spec runner page.
     *
     * @return string
     */
    public function out() {

        $output  = '';

        $output .= \tool_jasmine\boilerplate::page_header();

        // Hides parts of the page we can't prevent being output
        // due to tight coupling of in core output renderers.
        $output .= \tool_jasmine\boilerplate::hide_page_element_styles();

        $output .= $this->jasmine_styles_html();
        $output .= $this->jasmine_scripts_html();

        $output .= $this->sinon_scripts_html();

        // Ensure things like require() are defined before
        // we try to use them in our specs.
        $output .= \tool_jasmine\boilerplate::page_footer();

        // Output the Jasmine specs.
        $specs = implode((PHP_EOL . PHP_EOL), $this->specs);
        $attributes = array('type' => 'text/javascript');
        $output .= \html_writer::tag('script', $specs, $attributes);


        return $output;
    }

    /**
     * @param $specs
     */
    public static function generate($url, $specs) {
        global $PAGE;

        \tool_jasmine\boilerplate::check_acceptance_site();
        \tool_jasmine\boilerplate::check_permissions();
        \tool_jasmine\boilerplate::init_page(new \moodle_url($url));
//        if (!is_array($specs)) {
//            $specs = array($specs);
//        }
//
//        $instance = new \tool_jasmine\spec_runner();
//
//        foreach ($specs as $spec) {}
        return 'woo';
    }
}