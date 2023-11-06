@work
Feature: Work adaptation cost create page

  Background:
    Given I load fixtures:
      | userCompany         |
      | work/workAdaptation |
    And I am logged on
    And I am on "/works/1/distribution-costs/add"

  Scenario: I check messages and redirection
    Then I should see "Ajouter un coût de distribution à l’œuvre Winnie the Pooh"
    When I press "Enregistrer"
    Then I should see "Cette valeur ne doit pas être vide" 2 times
    When I follow "Retour"
    Then I should be on "/works/1/distribution-costs"

  Scenario: I create a work adaptation cost
    When I fill in "work_adaptation_form[type]" with "2"
    And I fill in "work_adaptation_form[amount]" with "100"
    And I fill in "work_adaptation_form[currency]" with "USD"
    And I fill in "work_adaptation_form[comment]" with "This is a comment about Subtitling cost"
    And I press "Enregistrer"
    Then I should be on "/works/1/distribution-costs"
    And I should see "3 enregistrements trouvés"
    And I should see a table with:
      | Type            | Montant    | Commentaire                             |
      | Subtitling cost | 100,00 $US | This is a comment about Subtitling cost |
