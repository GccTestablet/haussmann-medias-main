@acquisitionContract
Feature: Acquisition contract show page

  Background:
    Given I load fixtures:
      | userCompany                  |
      | contract/acquisitionContract |
      | work/work                    |
      | work/workTerritory           |
    And I am logged on
    And I am on "/acquisition-contracts/1"

  Scenario: We check page informations
    Then I should see "Winnie the Pooh"
    And I should see "Date de signature 01/01/2023"
    And I should see "Date de début de droits 01/01/2023"
    And I should see "Date de fin de droits 31/12/2023"
    And I should see "Date de fin de droits 31/12/2023"
    And I should see "Commentaire sur les rapports"
    And I should see "Fichiers"

  @javascript
  Scenario: We check the work list
    Then I should see "Ajouter une œuvre"
    And I should see a table with:
      | ID interne | Titre français  |
      | HAU000001  | Winnie the Pooh |
    When I click on "[data-work-id='1']"
    Then I wait to see "Territoires et canaux acquis"
    And I should see a table "#work-1-territories-table" with:
      | Territoire     | Canaux           |
      | France         | AVOD, SVOD       |
      | United Kingdom | AVOD, SVOD, TVOD |