[![Build Status](https://travis-ci.org/jobyh/tool_jasmine.svg?branch=2.0-development)](https://travis-ci.org/jobyh/tool_jasmine)

# JavaScript testing for Moodle & Totara

**This is the development branch for 2.0 this documentation is incomplete**

Integrates the [Jasmine](https://github.com/jasmine/jasmine) BDD test framework with [Moodle](https://moodle.org/) or [Totara](https://www.totaralms.com/). Jasmine provides a nice HTML based spec runner out of the box and can be integrated easily with the Moodle / Totara platforms. [Sinon](http://sinonjs.org/) is also included for creation of mocks, stubs and spies.

## Overview
A somewhat eccentric tool for JavaScript testing. **TODO**

### What it does
- Gives you the means to write tests for JavaScript in Moodle and Totara
- Provides a way to automate running your tests e.g. for Continuous Integration
- Allows you to integrate tests with your codebase or store them separately

### What it doesn't
- Provide a way to extract metrics such as code coverage for your JavaScript

## Requirements
- Moodle or Totara version 2.9.0+
- A sensible browser **TODO**

## Installation

```
% cd path/to/your/$CFG->dirroot
% git clone https://github.com/jobyh/tool_jasmine.git admin/tool/jasmine
```

Usual plugin installation drill. Navigate to `Site administration > notifications` and follow the onscreen instructions. Also initialise your Behat [acceptance testing](https://docs.moodle.org/dev/Running_acceptance_test) site from which specs will be accessed.

## Writing specs

In order to satisfy security requirements and mitigate potential data loss JavaScript specs must be contained within PHP files and can only be accessed from an initialised [acceptance testing](https://docs.moodle.org/dev/Running_acceptance_test) (Behat) site URL the base of which you will have defined in `$CFG->behat_wwwroot`. The username / password when logging in to the acceptance site is automatically configured during Behat initialisation. In 2.9 it is `admin`, `admin`.

Specs are impelemeted as Behat test fixtures and must be located accordingly inside a directory called `tool_jasmine`. Spec files must end with the `_spec.js.php` suffix. You may find it a useful convention to keep a 1:1 association between your JS modules and specs. E.g. the following example tests an AMD module called `mymodule` found in a local plugin `foo`: 

```php
// local/foo/tests/behat/fixtures/tool_jasmine/mymodule_spec.js.php

require_once('../../config.php');

// Editors like PHPStorm will correctly syntax highlight the following
// block as JavaScript if we use 'JS' Heredoc delimiters.
$spec = <<<JS

    describe('local_foo/mymodule AMD module', function() {
        it('can say Hi!', function(done) {
            require(['local_foo/mymodule'], function(mymodule) {
                expect(mymodule.sayHi()).toBe('Hi!');
                done();
            })
        });
    });

JS;

$url = '/local/foo/tests/behat/fixtures/tool_jasmine/mymodule_spec.js.php';

// Performs necessary access checks then generates HTML.
echo \tool_jasmine\spec_runner::generate($url, $spec);
```

View and debug your test results by navigating to the PHP file in a browser.

## Automation via Behat

A command-line script takes care of generating Behat feature files which can be run to check whether Jasmine specs are passing intended primarily for integration with build pipelines. Each plugin containing Jasmine specs will have its own Behat feature file written to a `tool_jasmine` directory in your `$CFG->dataroot`.

```
% php /path/to/your/$CFG->dirroot/admin/tool/jasmine/cli/init.php
```

Provided you have initialised [acceptance testing](https://docs.moodle.org/dev/Running_acceptance_test) you can run all these Behat features using the standard Behat parameters and specifying the **absolute** path to the generated directory:

```
% cd /path/to/your/$CFG->dirroot
% vendor/bin/behat --config=/path/to/behat.yml /path/to/$CFG->dataroot/tool_jasmine/
```

## FAQs
- **Why Jasmine?** TODO

## License
[GNU GPL v3 or later](http://www.gnu.org/copyleft/gpl.html)

## Credits
[Jasmine](https://github.com/jasmine/jasmine) is Copyright (c) 2008-2016 Pivotal Labs and licensed under the MIT License.

[Sinon](http://sinonjs.org/) is Copyright (c) 2010-2014 Christian Johansen and licensed under the BSD License.
