@distributionContract
Feature: Distribution contract list page

  Background:
    Given I load fixtures:
      | userCompany                       |
      | contract/distributionContractWork |
    And I am logged on
    And I am on "/distribution-contracts"

  Scenario: I check if links are working as expected
    When I follow "Ajouter un contrat de sous-distribution"
    Then I should be on "/distribution-contracts/add"

    When I am on "/distribution-contracts"
    And I follow "MW - Winnie the Pooh"
    Then I should be on "/distribution-contracts/1/works"

    When I am on "/distribution-contracts"
    And I follow "Mediawan"
    Then I should be on "/companies/4"

  Scenario: I check if datatable has expected rows
    Then I should see "Liste des contrats de sous-distribution"
    And I should see "1 enregistrements trouvés"
    And I should see a table with:
      | Nom                  | Vendeur          | Distributeur | Canaux de diffusion | Œuvres          |
      | MW - Winnie the Pooh | Haussmann Medias | Mediawan     | AVOD, SVOD, TVOD    | Winnie the Pooh |
    When I switch to company "Chrome Films"
    Then I should see "1 enregistrements trouvés"
    And I should see a table with:
      | Nom                       | Vendeur      | Distributeur       | Canaux de diffusion | Œuvres           |
      | MDC - Sniper and Maneater | Chrome Films | My Digital Company | AVOD, SVOD, TVOD    | Maneater, Sniper |