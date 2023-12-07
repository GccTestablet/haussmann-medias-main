@user
Feature: User create page

  Background:
    Given I load fixtures:
      | user                  |
    And I am logged on
    And I am on "/users/add"

  Scenario: We check page title and redirection
    Then I should see "Ajouter un utilisateur"
    When I follow "Retour"
    Then I should be on "/users"

  Scenario: We check form errors
    When I press "Enregistrer"
    Then I should see "Cette valeur ne doit pas être vide" 4 times

  Scenario: We check with valid data
    When I select "Super administrator" from "user_form[role]"
    And I fill in the following:
      | user_form[firstName]     | Martin  |
      | user_form[lastName] | DUPONT               |
      | user_form[email] |  martin-dupont@hm.mail              |
    And I press "Enregistrer"
    Then I should be on "/users/3"
    And I should see "Martin DUPONT"
    When I open mail from "no-reply@haussmann-medias.com"
    Then I should see mail to "martin-dupont@hm.mail"
    And I should see mail with subject "Création de votre compte"
    And I should see "Bonjour Martin DUPONT" in mail
    And I should see "Votre compte a été créée" in mail

