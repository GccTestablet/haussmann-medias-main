@distributionContract
Feature: Distribution contract show page

  Background:
    Given I load fixtures:
      | userCompany                                |
      | contract/distributionContractWorkTerritory |
      | work/workTerritory                         |
    And I am logged on
    And I am on "/distribution-contracts/1"

  Scenario: We check page informations
    Then I should see "Contrat de sous-distribution MW - Winnie the Pooh"
    And I should see "Nom du contrat: MW - Winnie the Pooh"
    And I should see "Date de signature: 01/10/2021"
    And I should see "Territoires: France, United Kingdom"
    And I should see "Canaux de diffusion: AVOD, SVOD, TVOD"
    And I should see "Exclusivité:"
    And I should see "Conditions commerciales:"
    And I should see "Fréquence des rapports: Mensuel"
    And I should see "Fichiers:"

  @javascript
  Scenario: We check the work list
    Then Debug: HTML content
    Then I should see "Ajouter une œuvre"
    And I should see a table with:
      | Œuvre                       | Période                 | Montant     |
      | Winnie the Pooh (HAU000001) | 01/01/2023 - 31/12/2023 | 50 000,00 € |
    When I click on "[data-work-id='1']"
    Then I should see "Territoires et canaux distribués"
    And I should see a table "#work-1-territories-table" with:
      | Territoire     | Exclusif | Canaux     |
      | France         | true     | AVOD, SVOD |
      | United Kingdom | true     | AVOD, SVOD |