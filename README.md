[![Build Status](https://travis-ci.org/jobyh/tool_jasmine.svg?branch=master)](https://travis-ci.org/jobyh/tool_jasmine)

#Jasmine BDD for Moodle & Totara

Integrates the [Jasmine](https://github.com/jasmine/jasmine) BDD test framework with [Moodle](https://moodle.org/) or [Totara](https://www.totaralms.com/). Jasmine provides a nice HTML based spec runner out of the box and can be integrated easily with the Moodle / Totara stack. [Sinon](http://sinonjs.org/) is also included for creation of mocks, stubs and spies.

The tool is intended for development purposes and not production use. By default generated spec runners require authentication and site admin permission to access (just in case you accidentally deployed one).

## Requirements
- Moodle or Totara version 2.9.0+

## Installation

```
% cd path/to/your/$CFG->dirroot
% git clone https://github.com/jobyh/tool_jasmine.git admin/tool/jasmine
```

Hit the Site administration > notifications page and follow the onscreen instructions.

## Write some tests
Create a Jasmine spec file under `amd/spec` in your plugin. The file can be named anything you want provided it has the suffix `_spec.js` but I strongly suggest you base it on the name of the AMD module it tests e.g. `mymodule_spec.js`.

There is one important addition: you must call the global function `specDone()` in **each** spec file once all your `describe()` calls have been made. Jasmine will only run your tests once all included specs have bootstrapped and called this function. e.g.

```javascript
// local/myplugin/amd/spec/mymodule_spec.js
require(['local_myplugin/mymodule'], function(MyPlugin) {
	describe('addOne', function() {
		it('should add one to a given number', function() {
			expect(MyPlugin.addOneTo(6)).toEqual(7);
		});
	});

	// MUST be called.
	specDone();
});
```

The `require()` above includes the AMD module under test. Remember that requiring AMD modules is asynchronous therefore calling `specDone()` *outside* the require call may cause Jasmine to boot before your tests have loaded (and the runner will report that none were found). Specs are not AMD modules and do not require an enclosing call to `define()`.

**Note:** `specDone()` is a custom function provided by the tool. *It is not core Jasmine.*

## Caveat
While not expecting anyone to be looking at your specs generally speaking remember that they are just JavaScript files and therefore accessible to anyone via the web without restriction. So don't put any sensitive information in them.

## Generate test runners

```
% php admin/tool/jasmine/cli/init.php
```

Your plugin `amd` directory should now contain a `spec_runner.php` file including your spec which can be accessed via browser to see your test results. It is not necessary to re-initialise whenever you make a change to your spec. I'd also recommend setting $CFG->cachejs to false so that you don't have to build your JS changes while developing.

There are also some example specs under `admin/tool/jasmine/amd/spec`. You can inspect their output at `<wwwroot>/admin/tool/jasmine/amd/spec_runner.php` once you've initialized.

The tool will include as many specs as are found under `amd/spec` but make sure they all call `specDone()` otherwise Jasmine won't boot. You will need to re-initialize when adding new spec files.

## Tidying up
We don't want to include `spec_runner.php` files in our production codebase once we've finished testing. You may choose to add `*/amd/spec_runner.php` to your `.git/info/excludes` or `.gitignore` file so that runners aren't accidentally staged / committed.

To remove all Jasmine spec runner files:

```
% cd path/to/dirroot
% php admin/tool/jasmine/cli/cleanup.php
```

## License
[GNU GPL v3 or later](http://www.gnu.org/copyleft/gpl.html)

## Credits
[Jasmine](https://github.com/jasmine/jasmine) is Copyright (c) 2008-2016 Pivotal Labs and licensed under the MIT License.

[Sinon](http://sinonjs.org/) is Copyright (c) 2010-2014 Christian Johansen and licensed under the BSD License.
