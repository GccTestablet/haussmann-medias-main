@work
Feature: Work reversion manage page

  Background:
    Given I load fixtures:
      | userCompany        |
      | work/workTerritory |
      | work/workReversion |
    And I am logged on
    And I am on "/works/1/reversions/manage"

  Scenario: I check error message
    When I load fixtures:
      | userCompany        |
      | work/workReversion |
    And I am logged on
    And I am on "/works/1/reversions/manage"
    And I should see "Remplir tout"
    And I should not see "AVOD"
    And I should not see "SVOD"
    And I should not see "TVOD"
    Then I should see "Aucun canal de diffusion disponible pour cette œuvre"
    And I should see "Veuillez modifier l'œuvre et ajouter des canaux de diffusion pour pouvoir continuer"
    When I follow "modifier l'œuvre"
    Then I should be on "/works/1/territories"

  Scenario: I check messages
    Then I should see "Gérer les reversements de l'œuvre Winnie the Pooh"
    And I should see a table with:
      | Remplir tout | AVOD | SVOD | TVOD |
      |              |      |      |      |
    And I should not see "Free TV"
    And the "work_reversion_form[reversions][broadcast_channel_1]" field should contain "50"
    And the "work_reversion_form[reversions][broadcast_channel_2]" field should contain "20"
    And the "work_reversion_form[reversions][broadcast_channel_3]" field should contain "80"
    When I follow "Retour"
    Then I should be on "/works/1/reversions"

  Scenario: I update reversions
    When I fill in "work_reversion_form[reversions][broadcast_channel_1]" with "10"
    And I fill in "work_reversion_form[reversions][broadcast_channel_2]" with "50"
    And I fill in "work_reversion_form[reversions][broadcast_channel_3]" with ""
    And I press "Enregistrer"
    Then I should be on "/works/1/reversions"
    And I should see "2 enregistrements trouvés"
    And I should see a table with:
      | Canal | Pourcentage ayant-droit |
      | AVOD  | 10                      |
      | SVOD  | 50                      |
    And I should not see a table with:
      | Canal |
      | TVOD  |