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

require_once(__DIR__ . '/../../../../../lib/behat/behat_base.php');

use \tool_jasmine\spec_finder;

class behat_tool_jasmine extends behat_base {

    /**
     * Navigate to a given Jasmine spec in a given plugin.
     *
     * Specs are intentionally omitted from navigation therefore
     * we navigate to the spec directly. The plugin parameter is
     * a frankenstyle plugin name. The spec parameter should be
     * the name of the spec without the spec suffix. Convention
     * is to provide a single spec file for each AMD module and
     * name it accordingly. E.g.
     *
     * local/myplugin/amd/src/foo.js => local/myplugin/tests/behat/fixtures/tool_jasmine/foo_spec.js.php
     *
     * @Given /^I navigate to the "([^"]*)" plugin "([^"]*)" Jasmine spec$/
     */
    public function i_navigate_to_the_x_plugin_x_jasmine_spec($plugin, $specname) {
        global $CFG;

        $plugindir = core_component::get_component_directory($plugin);
        $relativepath = substr($plugindir, 0, strlen($CFG->dirroot));

        $specfile = $specname . spec_finder::SPEC_SUFFIX;
        $specpath = implode('/', [$relativepath, spec_finder::PLUGIN_SPEC_DIR, $specfile]);

        $url = new moodle_url($specpath);
        $this->getSession()->visit($url->out(false));
    }

    /**
     * Check that the Jasmine spec on the current page is passing.
     *
     * @Given /^I should see that the Jasmine spec has passed$/
     */
    public function i_should_see_that_the_jasmine_spec_has_passed() {
        $xpath = '//*[contains(@class, "jasmine-bar")][contains(text(), "0 failures")]';
        $session = $this->getSession();
        $url = $this->getSession()->getCurrentUrl();
        $message = "The Jasmine spec at {$url} failed";
        $exception = new \Behat\Mink\Exception\ExpectationException($message, $session);
        $this->find('xpath', $xpath, $exception);
    }
}