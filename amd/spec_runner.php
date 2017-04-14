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

require_once("/var/www/html/www/config.php");

global $CFG, $USER, $PAGE;

require_login();
require_capability('moodle/site:config', context_system::instance(), $USER->id);

$reporter = optional_param('reporter', '', PARAM_ALPHA);
$reporter = in_array($reporter, array('tap', 'junit')) ? $reporter : '';

$PAGE->set_context(context_system::instance());

?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <title>Jasmine Spec Runner v2.5.2</title>

    <link rel="shortcut icon" type="image/png" href="http://vagrant.localhost/admin/tool/jasmine/lib/jasmine-2.5.2/jasmine_favicon.png">
    <link rel="stylesheet" href="http://vagrant.localhost/admin/tool/jasmine/lib/jasmine-2.5.2/jasmine.css">

    <script src="http://vagrant.localhost/admin/tool/jasmine/lib/jasmine-2.5.2/jasmine.js"></script>
    <script src="http://vagrant.localhost/admin/tool/jasmine/lib/jasmine-2.5.2/jasmine-html.js"></script>

    <script src="http://vagrant.localhost/admin/tool/jasmine/lib/jasmine-2.5.2/boot.js"></script>

    <script src="http://vagrant.localhost/admin/tool/jasmine/lib/sinon-1.17.3/sinon-1.17.3.js"></script>

    <?php

    // This head code contains sesskey so cannot be output statically.
    echo $PAGE->requires->get_head_code($PAGE, new core_renderer($PAGE, null));

    ?>

    <style>
        /*
         * TODO remove this once we
         * can avoid using headcode.
         */
        .skiplinks {
            display: none;
        }
    </style>

</head>

<body>
    <script>
        // This facilitates initializing
        // Jasmine once all of the included
        // spec scripts are complete (requires
        // each script to call specDone() when
        // all tests have been defined).
        var numSpecs = 2;
        var specDone = (function() {
            var t = window.setTimeout(function() {
                document.body.innerHTML = ''
                    + '<style>* { border: 0; margin: 0; padding: 0; }'
                    + 'html { padding: 1.25rem 1.5rem;'
                    + 'font-family: Helvetica Neue, Arial, Sans-serif; }'
                    + 'h1 { padding-bottom: 0.5rem; }</style>'
                    + '<h1>Timeout</h1>'
                    + '<p>Did you remember to call <code>specDone()</code> '
                    + 'after your tests in each spec file?</p>';
                throw new Error('Test execution was not triggered');
            }, (numSpecs * 2000));

            var count = 0;
            return function () {
                if (count < numSpecs - 1) {
                    return count += 1;
                }
                window.clearTimeout(t);
                return window.executeJasmineTests();
            };
        })();

    </script>
    <div class="skiplinks"><a class="skip" href="#maincontent">Skip to main content</a></div>
<script type="text/javascript" src="http://vagrant.localhost/theme/yui_combo.php?rollup/3.17.2/yui-moodlesimple.js&amp;rollup/-1/mcore-debug.js"></script><script type="text/javascript" src="http://vagrant.localhost/lib/javascript.php/-1/lib/javascript-static.js"></script>
<script type="text/javascript">
//<![CDATA[
document.body.className += ' jsenabled';
//]]>
</script>


    <script type="text/javascript">
//<![CDATA[
var require = {
    baseUrl : 'http://vagrant.localhost/lib/requirejs.php/-1/',
    // We only support AMD modules with an explicit define() statement.
    enforceDefine: true,
    skipDataMain: true,

    paths: {
        jquery: 'http://vagrant.localhost/lib/javascript.php/-1/lib/jquery/jquery-1.12.1.min',
        jqueryui: 'http://vagrant.localhost/lib/javascript.php/-1/lib/jquery/ui-1.11.4/jquery-ui.min',
        jqueryprivate: 'http://vagrant.localhost/lib/javascript.php/-1/lib/requirejs/jquery-private'
    },

    // Custom jquery config map.
    map: {
      // '*' means all modules will get 'jqueryprivate'
      // for their 'jquery' dependency.
      '*': { jquery: 'jqueryprivate' },

      // 'jquery-private' wants the real jQuery module
      // though. If this line was not here, there would
      // be an unresolvable cyclic dependency.
      jqueryprivate: { jquery: 'jquery' }
    }
};

//]]>
</script>
<script type="text/javascript" src="http://vagrant.localhost/lib/javascript.php/-1/lib/requirejs/require.js"></script>
<script type="text/javascript">
//<![CDATA[
require(['core/first'], function() {
;
require(["core/log"], function(amd) { amd.setConfig({"level":"trace"}); });
});
//]]>
</script>
<script type="text/javascript">
//<![CDATA[
M.str = {"moodle":{"lastmodified":"Last modified","name":"Name","error":"Error","info":"Information","yes":"Yes","no":"No","cancel":"Cancel","confirm":"Confirm","areyousure":"Are you sure?","closebuttontitle":"Close","unknownerror":"Unknown error"},"repository":{"type":"Type","size":"Size","invalidjson":"Invalid JSON string","nofilesattached":"No files attached","filepicker":"File picker","logout":"Logout","nofilesavailable":"No files available","norepositoriesavailable":"Sorry, none of your current repositories can return files in the required format.","fileexistsdialogheader":"File exists","fileexistsdialog_editor":"A file with that name has already been attached to the text you are editing.","fileexistsdialog_filemanager":"A file with that name has already been attached","renameto":"Rename to \"{$a}\"","referencesexist":"There are {$a} alias\/shortcut files that use this file as their source","select":"Select"},"admin":{"confirmdeletecomments":"You are about to delete comments, are you sure?","confirmation":"Confirmation"},"block":{"addtodock":"Move this to the dock","undockitem":"Undock this item","dockblock":"Dock {$a} block","undockblock":"Undock {$a} block","undockall":"Undock all","hidedockpanel":"Hide the dock panel","hidepanel":"Hide panel"},"langconfig":{"thisdirectionvertical":"btt"}};
//]]>
</script>
<script type="text/javascript">
//<![CDATA[
(function() {Y.use("moodle-core-dock-loader",function() {M.core.dock.loader.initLoader();
});
M.util.init_skiplink(Y);
 M.util.js_pending('random58034b99e67b42'); Y.on('domready', function() { M.util.js_complete("init");  M.util.js_complete('random58034b99e67b42'); });
})();
//]]>
</script>


        <script src="http://vagrant.localhost/admin/tool/jasmine/amd/spec/example2_spec.js"></script>
        <script src="http://vagrant.localhost/admin/tool/jasmine/amd/spec/example_spec.js"></script>
</body>
</html>