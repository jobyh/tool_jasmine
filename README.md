[![Build Status](https://travis-ci.org/jobyh/tool_jasmine.svg?branch=2.0-development)](https://travis-ci.org/jobyh/tool_jasmine)

# JavaScript testing for Moodle & Totara

**This is the development branch for 2.0 this documentation is incomplete**

Integrates the [Jasmine](https://github.com/jasmine/jasmine) BDD test framework with [Moodle](https://moodle.org/) or [Totara](https://www.totaralms.com/). Jasmine provides a nice HTML based spec runner out of the box and can be integrated easily with the Moodle / Totara platforms. [Sinon](http://sinonjs.org/) is also included for creation of mocks, stubs and spies.

## Overview
A somewhat eccentric tool for JavaScript testing.

**TODO**

### What it does
- Gives you the means to write test-driven JavaScript in Moodle and Totara
- Supports AMD, YUI and JavaScript lurking in the global namespace when you're legacy bushwacking
- Provides a way to automate running your tests
- Allows you to integrate spec files with your codebase or store them separately

### What it doesn't
- Provide a way to extract metrics such as code coverage for your JavaScript

## Requirements
- Moodle or Totara version 2.9.0+
- PHP 5.4.4+
- A sensible browser **TODO**

## Installation

```
% cd path/to/your/$CFG->dirroot
% git clone https://github.com/jobyh/tool_jasmine.git admin/tool/jasmine
% cd admin/tool/jasmine && git checkout 2.0-development
```

Usual plugin installation drill. Navigate to `Site administration > notifications` and follow the onscreen 
instructions. Also initialise your Behat [acceptance testing](https://docs.moodle.org/dev/Running_acceptance_test) 
site from which specs will be accessed. Ensure that you have developer debugging enabled.

Once this is done navigate to:

```
<$CFG->behat_wwwroot>/admin/tool/jasmine/tests/behat/fixtures/jasmine/example_spec.php
```

To see an example of the Jasmine runner. Note you will be prompted to log in to the acceptance site. The username / password is `admin` / `admin`.

## Configuration
In order to generate Behat features using the command-line script (discussed below) tool_jasmine requires that you set `$CFG->tool_jasmine_dataroot` in your `config.php` file. Like the standard `$CFG->dataroot` property this directory should not be accessible from the web.

## Spec Location
Depending on what you're developing / buy-in from stakeholders you may or may not wish to include Jasmine specs in your LMS repository. To accommodate both scenarios specs may be placed in the following directories:

- Any plugin under `tests/behat/fixtures/jasmine/`
- In tool_jasmine under `admin/tool/jasmine/tests/behat/fixtures/jasmine/<frankenstyle_componentname>/`

The first approach is intended where specs are to be committed into the main LMS repository. The second so that all tests can be stored in a standalone fork of tool\_jasmine which can be dropped in for testing when required. If for some reason both are used then the spec in tool\_jasmine is given precedence.

### Accessing Spec Runners

In order to satisfy security requirements and mitigate potential data loss JavaScript specs must be contained within PHP files. This is so they can only be accessed from an initialised Behat ([acceptance testing](https://docs.moodle.org/dev/Running_acceptance_test)) site. You will have defined the URL base of this site in `$CFG->behat_wwwroot` before initialising Behat. Developer debugging must also be enabled in your `config.php` file and you must be logged in as an administrator. The username / password for the administrator account in the Behat site is automatically configured during Behat initialisation and is `admin`, `admin`.

## Writing specs

**Note:** For examples using AMD, YUI and JS in the global namespace check out the example spec file `tests/behat/fixtures/jasmine/example_spec.php`

Spec files must end with the `_spec.php` suffix. You may find it a useful convention to keep a 1:1 association 
between your JS modules and specs. E.g. the following example tests an AMD module called `mymodule` found in a local plugin `foo`: 

```javascript
// file: local/foo/tests/behat/fixtures/tool_jasmine/mymodule_spec.php

// License and author attribution here.

require_once('../../config.php');

// Editors like PHPStorm will correctly syntax highlight the following
// block as JavaScript if we use 'JS' Heredoc delimiters.
$spec = <<<JS

    // Write your Jasmine test JavaScript here...
    describe('local_foo/mymodule AMD module', function() {
    
        // AMD modules are required asynchronously
        // so use Jasmine's async form with done() function.
        it('can say Hi!', function(done) {
            require(['local_foo/mymodule'], function(mymodule) {
                expect(mymodule.sayHi()).toBe('Hi!');
                done();
            })
        });
    
    });

JS;

// We must provide the current page URL as we would usually pass to $PAGE->set_url().
$url = '/local/foo/tests/behat/fixtures/tool_jasmine/mymodule_spec.php';

// Performs necessary access checks then generates spec runner HTML.
echo \tool_jasmine\spec_runner::generate($url, $spec);
```

View and debug your test results by navigating to the PHP file in a browser. The spec runner for the above would be accessible in a browser at:

 ```
 <$CFG->behat_wwwroot>/local/foo/tests/behat/fixtures/tool_jasmine/mymodule_spec.php
 ```

## Automation via Behat

A command-line script takes care of generating Behat feature files which can be run to check whether Jasmine specs 
are passing intended primarily for integration with build pipelines. Each plugin containing Jasmine specs will have 
its own Behat feature file written to a `features` directory under `$CFG->tool_jasmine_dataroot` which you specify in 
your `config.php`.

```
% php /path/to/your/$CFG->dirroot/admin/tool/jasmine/cli/init.php
```

Provided you have initialised [acceptance testing](https://docs.moodle.org/dev/Running_acceptance_test) you can run all these Behat features using the standard Behat parameters and specifying the **absolute** path to the generated directory:

```
% cd /path/to/your/$CFG->dirroot
% vendor/bin/behat --config=/path/to/behat.yml /path/to/$CFG->tool_jasmine_dataroot/features/
```

## FAQs
- **Why Jasmine?** TODO
- **Why automate with Behat?** TODO

## Contributing
**TODO**

## License
[GNU GPL v3 or later](http://www.gnu.org/copyleft/gpl.html)

## Credits
[Jasmine](https://github.com/jasmine/jasmine) is Copyright (c) 2008-2016 Pivotal Labs and licensed under the MIT License.

[Sinon](http://sinonjs.org/) is Copyright (c) 2010-2014 Christian Johansen and licensed under the BSD License.
