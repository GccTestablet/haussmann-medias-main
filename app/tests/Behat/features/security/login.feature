@security
Feature: Login page
  Background:
    Given I load fixtures:
      | user |

  Scenario: Login with invalid credentials
    When I am on "/login"
    And I fill in "login_form[email]" with "super-admin@hm.mail"
    And I fill in "login_form[password]" with "INVALID_PASSWORD"
    And I press "Connexion"
    Then I should see "Identifiants invalides"

  Scenario: Login with valid credentials
    When I am on "/login"
    And I fill in "login_form[email]" with "super-admin@hm.mail"
    And I fill in "login_form[password]" with "Qwerty123"
    And I press "Connexion"
    Then I should be on "/"
    Then I should see "Dashboard"
