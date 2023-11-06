@work
Feature: Work adaptation cost create page

  Background:
    Given I load fixtures:
      | userCompany         |
      | work/workAdaptation |
    And I am logged on
    And I am on "/works/1/distribution-costs/1/update"

  Scenario: I check messages and redirection
    Then I should see "Mettre à jour le coût de distribution de l'œuvre Winnie the Pooh"
    When I follow "Retour"
    Then I should be on "/works/1/distribution-costs"

  Scenario: I create a work adaptation cost
    And I fill in "work_adaptation_form[amount]" with "1500"
    And I fill in "work_adaptation_form[comment]" with "This is a comment about dubbing cost"
    And I press "Enregistrer"
    Then I should see "2 enregistrements trouvés"
    And I should see a table with:
      | Type         | Montant    | Commentaire                          |
      | Dubbing cost | 1 500,00 € | This is a comment about dubbing cost |
