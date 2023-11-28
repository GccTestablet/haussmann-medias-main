@security
Feature: Reset password page

  Background:
    Given I load fixtures:
      | user |

  Scenario: When user want to reset wrong password
    Given I am on "/"
    And I follow "Mot de passe oublié ?"
    Then I should be on "/reset-password/request"
    And I should see "Veuillez entrer l'adresse email utilisée pour recevoir un lien de réinitialisation de mot de passe"
    When I fill in the following:
      | request_reset_password_form[email] | email@notfound.com |
    And I press "Envoyer la demande"
    Then I should see "Un email a été envoyé. Il contient un lien sur lequel vous devez cliquer pour réinitialiser votre mot de passe."
    And I should see "Vous ne pouvez demander un nouveau mot de passe que toutes les 1 minutes."
    And I should see "Si vous ne recevez pas d'email, vérifiez votre dossier spam ou réessayez."
    And 0 mail should be sent

  Scenario: When user want to reset his password
    Given I am on "/"
    And I follow "Mot de passe oublié ?"
    Then I should be on "/reset-password/request"
    And I should see "Veuillez entrer l'adresse email utilisée pour recevoir un lien de réinitialisation de mot de passe"
    When I fill in the following:
      | request_reset_password_form[email] | super-admin@hm.mail |
    And I press "Envoyer la demande"
    Then 0 mail should be sent
    And I consume messages
    Then I should see "Un email a été envoyé. Il contient un lien sur lequel vous devez cliquer pour réinitialiser votre mot de passe."
    And I should see "Vous ne pouvez demander un nouveau mot de passe que toutes les 1 minutes."
    And I should see "Si vous ne recevez pas d'email, vérifiez votre dossier spam ou réessayez."
    When I open mail from "no-reply@haussmann-medias.com"
    Then I should see mail to "super-admin@hm.mail"
    And I should see mail with subject "Réinitialisation de votre mot de passe"
    And I should see "Bonjour SUPER Admin" in mail
    And I should see "Afin de réinitialiser votre mot de passe, veuillez cliquer sur le lien suivant." in mail
    And I should see "Réinitialiser votre mot de passe" in mail
    And I reset the password for "super-admin@hm.mail" with "Azerty123456"