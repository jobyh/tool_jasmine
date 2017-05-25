[![Build Status](https://travis-ci.org/jobyh/tool_jasmine.svg?branch=2.0-development)](https://travis-ci.org/jobyh/tool_jasmine)

# JavaScript testing for Moodle & Totara

Integrates the [Jasmine](https://github.com/jasmine/jasmine) BDD test framework with [Moodle](https://moodle.org/) or [Totara](https://www.totaralms.com/). Jasmine was designed to run in-browser and plays nicely with the  and provides a nice HTML based spec runner out of the box. [Sinon](http://sinonjs.org/) is also included at present for creation of mocks, stubs and spies.

We're getting there but this is an Alpha release so constructive feedback and bug reports appreciated!

## What it does
- Gives you the means to test JavaScript in Moodle and Totara
- Provides a way to automate running your JavaScript tests
- Supports AMD, YUI and JavaScript lurking in the global namespace when you're legacy bushwacking

## What it doesn't (at present)
- Provide a way to extract metrics such as code coverage for your JavaScript

## Hows it work then?
1. You write PHP spec files containing your test JavaScript using the API provided
2. You view the test results in the browser of your choice while developing your code
3. You write a Behat feature file using the step definitions provided
4. You let CI pick up the JS failures when you or a colleague cause a regression

**Top tip:** This repository automatically runs its Jasmine spec this way powered by the excellent [moodle-plugin-ci](https://github.com/moodlerooms/moodle-plugin-ci).

## That's a bit wierd isn't it?
Yes. In a nutshell tight coupling and hidden dependencies in the core APIs make it unfeasible to consume JS (even the AMD stuff) from anywhere apart from a bootstrapped LMS page. As [Joe Armstrong's](https://en.wikipedia.org/wiki/Joe_Armstrong_(programming)) famous observation puts it:

> "You wanted a banana but what you got was a gorilla holding the banana and the entire jungle."

## Requirements
- Moodle or Totara version 2.9.0+ (actually it *should* work with earlier versions but not tested yet - the AMD test in the example spec will fail for sure...)
- PHP 5.4.4+
- A browser also supported by the bundled Jasmine version

## Installation

```
% cd path/to/your/$CFG->dirroot
% git clone https://github.com/jobyh/tool_jasmine.git admin/tool/jasmine
% cd admin/tool/jasmine && git checkout 2.0-development
```

1. Navigate to `Site administration > notifications` and follow the onscreen 
instructions.
2. Initialise your Behat [acceptance testing](https://docs.moodle.org/dev/Running_acceptance_test) 
site from which specs will be accessed.
3. Log in to your **Behat acceptance site** (username / password is `admin` / `admin`)
4. See the example spec at:

```
<$CFG->behat_wwwroot>/admin/tool/jasmine/tests/behat/fixtures/jasmine/example_spec.php
```

## Writing specs

Time to get busy with the fizzy. First up **R**ead **t**he **F**abulous **M**anual(s). You should familiarise yourself with Jasmine by reading their [documentation](https://jasmine.github.io/pages/docs_home.html) and make sure you have a look at the example spec in this repository which has examples of tests for AMD, YUI and plain old JavaScript.

### Look I done a test!

In order for the bundled Behat step definition to find spec files you'll need to place them under the directory `tests/behat/fixtures/jasmine/` in the corresponding plugin. Based around Jasmine's convention all spec files must end in `_spec.php`.

Let's pretend we're writing a test for hypothetical plugin `local_foo`. I'm creating an AMD module called `bar` and want to test it. I create a spec file at `local/foo/tests/behat/fixtures/jasmine/bar_spec.php`. Inside that file I first include the site `config.php` file:

```php
<?php
require('../../../../../config.php');
```

I then write my Jasmine tests inside 'JS' Heredoc delimiters (or a string but PHPStorm is smart enough to correctly syntax highlight the JS Heredoc). There is an important reason the JavaScript must be inside a PHP file - if it were a plain JavaScript file we would have no control over access which could lead to accidental data loss depending on what the test involved. This is mitigated by allowing access on the acceptance site only. It also means that we can enforce permissions and attempt to make the environment consistent for testing.

```javascript
<?php
require('../../../../../config.php');

$specs =<<<JS
describe('AMD module local_foo/bar', function() {

    it('can exclaim given types of bar', function(done) {
        require('local_foo/bar', function(bar) {
        	expect(bar.exclaim('chocolate)).toBe('chocolate bar!');
        	expect(bar.exclaim('nut')).toBe('nut bar!');
        	done();
        });
    });

);
JS;
```

Finally output the string returned by convenience method `\tool_jasmine\spec_runner::generate` passing it the current page's URL and the specs. Note that you may optionally pass an array of specs.

```php
<?php
require('../../../../../config.php');

$specs =<<<JS
describe('AMD module local_foo/bar', function() {

    it('can exclaim given types of bar', function(done) {
        require('local_foo/bar', function(bar) {
        	expect(bar.exclaim('chocolate)).toBe('chocolate bar!');
        	expect(bar.exclaim('nut)).toBe('nut bar!');
        	done();
        });
    });

);
JS;

// As you would pass to \moodle_url().
$url = '/local/foo/tests/behat/fixtures/bar_spec.php';
echo \tool_jasmine\spec_runner::generate($url, $spec);
```

This call takes care of all the necessary permission / environment checks and boilerplate. If we wanted to view this we'd log in to our initialised acceptance site and navigate directly to the PHP script.

## Automation via Behat

Now we've written a spec (above) we want Behat to check if all tests are passing so we can integrate with our existing build pipeline. There is no additional naming requirement imposed on Behat specs. Just pick something sensible which groups specs nicely for your purposes. For our hypothetical plugin `local_foo` let's say we have a Jasmine spec for each of a handful of AMD modules. Lets create a single feature file at `local/foo/tests/behat/local_foo_specs.feature`. We use `tool_jasmine` step definitions to test each of the AMD modules: bar, baz and boz.

```
@local_foo @javascript @jasmine
Feature: tool_jasmine JavaScript

Scenario: local_foo Jasmine specs pass
Given I log in as "admin"
And I navigate to the "local_foo" plugin "bar" Jasmine spec
Then I should see that the Jasmine spec has passed
And I navigate to the "local_foo" plugin "baz" Jasmine spec
Then I should see that the Jasmine spec has passed
And I navigate to the "local_foo" plugin "boz" Jasmine spec
Then I should see that the Jasmine spec has passed
```

That's it!

## Contributing
**TODO**

## License
[GNU GPL v3 or later](http://www.gnu.org/copyleft/gpl.html)

## Credits
[Jasmine](https://github.com/jasmine/jasmine) is Copyright (c) 2008-2016 Pivotal Labs and licensed under the MIT License.

[Sinon](http://sinonjs.org/) is Copyright (c) 2010-2014 Christian Johansen and licensed under the BSD License.
