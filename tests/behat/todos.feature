@tool @tool_roland04
Feature: Viewing TODO list

  Background:
    Given the following "users" exist:
    | username 	  | firstname	| lastname  | email          	  |
    | testuser-01 | Test User | 01        | test01@local.host	|
    | testuser-02 | Test User | 02        | test02@local.host	|
    And the following "courses" exist:
    | fullname          | shortname   |
    | Activity examples | AE          |
    And the following "course enrolments" exist:
    | user    	  | course  | role	          |
    | testuser-01 | AE      | editingteacher  |
    | testuser-02 | AE      | student      	  |

  Scenario: Creating TODO List with editingteacher role
    When I log in as "testuser-01"
    And I am on "Activity examples" course homepage with editing mode on
    And I navigate to "TODO List" in current page administration
    Then I should see "View TODOs"
    And I should see "Add new TODO"
    When I click on "Add new TODO" "button"
    And I set the following fields to these values:
    | Name | TODO-01 |
    | Priority | 2 |
    And I press "Save changes"
    Then I should see "View TODOs"
    And I should see "TODO-01"
    And I should see "High"
    And "Edit TODO" "icon" should exist
    And "Delete TODO" "icon" should exist
    When I click on "Edit TODO" "link"
    And I set the following fields to these values:
    | Name | TODO-edited |
    | Priority | 0 |
    And I press "Save changes"
    Then I should not see "TODO-01"
    And I should see "TODO-edited"
    And I should see "Low"
    When I click on "Delete TODO" "link"
    Then I should not see "TODO-edited"

  Scenario: Accesing TODO List with student role
    When I log in as "testuser-02"
    And I am on "Activity examples" course homepage
    And I navigate to "TODO List" in current page administration
    Then I should see "View TODOs"
    And I should not see "Add new TODO"