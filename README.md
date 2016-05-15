#Jasmine BDD for Moodle & Totara

Integrates the [Jasmine](https://github.com/jasmine/jasmine) BDD test framework with [Moodle](https://moodle.org/) or [Totara](https://www.totaralms.com/). Jasmine provides a nice HTML based spec runner out of the box and can be integrated easily with the Moodle / Totara stack. [Sinon](http://sinonjs.org/) is also included for creation of mocks, stubs and spies.

The tool is intended for development / CI purposes and not production use. By default generated spec runners require authentication and site admin permission to access (just in case you accidentally deployed one).

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
			expect(MyPlugin.addOne(6)).toEqual(7);
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
We don't want to include `spec_runner.php` files in our production codebase once we've finished testing. You may choose to add `*/amd/spec_runner.php` to your `.gitignore` so that runners aren't accidentally staged / committed.

To remove all Jasmine spec runner files:

```
% cd path/to/dirroot
% php admin/tool/jasmine/cli/cleanup.php
```

## Advanced usage
### CI integration
To avoid having to write authentication boilerplate to access the spec runners additional options may be passed so that auth is not required. The `--no-auth` flag will prompt for verification before generating runners. Using the `-y` (assume yes) flag in addition will suppress the prompt.

An option `--custom-js` can be used to pass inline JavaScript you want adding to each spec runner *after*
all JavaScript includes but before the actual specs are included. The following example illustrates writing XML to the current working directory with a custom Jasmine reporter.

You can install [phantomjs](http://phantomjs.org/) using [npm](https://www.npmjs.com/) if you don't already have it.

```
% npm install -g phantomjs
```

Then we'll clone [jasmine-reporters](https://github.com/larrymyers/jasmine-reporters) by Larry Myers and initialise all spec runners including custom JS based on his example code so that we can output results to the local filesystem:

```
% git clone https://github.com/larrymyers/jasmine-reporters.git ~/jasmine-reporters
% cd /path/to/lms/dirroot
% php admin/tool/jasmine/cli/init.php --no-auth -y \
    --custom-js="$(cat ~/jasmine-reporters/src/junit_reporter.js); var junitReporter = new jasmineReporters.JUnitXmlReporter({ savePath: '..', consolidateAll: false }); jasmine.getEnv().addReporter(junitReporter);"
```

Finally we use `phantomjs` to generate the JUnit XML output for one of our specs in the current working directory:

```
% ~/jasmine-reporters/bin/phantomjs.runner.sh <wwwroot>/path/to/amd/spec_runner.php
```

## License
[GNU GPL v3 or later](http://www.gnu.org/copyleft/gpl.html)

## Credits
[Jasmine](https://github.com/jasmine/jasmine) is Copyright (c) 2008-2016 Pivotal Labs is licensed under the MIT License.
