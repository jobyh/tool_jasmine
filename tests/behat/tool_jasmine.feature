@tool_jasmine @javascript @jasmine
Feature: tool_jasmine JavaScript

Scenario: tool_jasmine Jasmine specs pass
Given I log in as "admin"
And I navigate to the "tool_jasmine" plugin "example" Jasmine spec
Then I should see that the Jasmine spec has passed
