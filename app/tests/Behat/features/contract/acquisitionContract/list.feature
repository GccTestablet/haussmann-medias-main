@acquisitionContract
Feature: Acquisition contract list page

  Background:
    Given I load fixtures:
      | userCompany                  |
      | contract/acquisitionContract |
      | work/work                    |
    And I am logged on
    And I am on "/acquisition-contracts"

  Scenario: I check if links are working as expected
    When I follow "Ajouter un contrat d'acquisition"
    Then I should be on "/acquisition-contracts/add"

    When I am on "/acquisition-contracts"
    And I follow "Winnie the Pooh"
    Then I should be on "/acquisition-contracts/1"

    When I am on "/acquisition-contracts"
    And I follow "HKA Films"
    Then I should be on "/companies/3"

  Scenario: I check if datatable has expected rows
    Then I should see "Liste des contrats d'acquisition"
    And I should see "1 enregistrements trouvés"
    And I should see a table with:
      | Nom                  | Acquéreur        | Ayant droit | Date de signature | Période de droits       | Œuvres          |
      | HF - Winnie the Pooh | Haussmann Medias | HKA Films   | 01/01/2023        | 01/01/2023 - 31/12/2023 | Winnie the Pooh |
    When I switch to company "Chrome Films"
    Then I should see "1 enregistrements trouvés"
    And I should see a table with:
      | Nom                      | Acquéreur    | Ayant droit | Date de signature | Période de droits | Œuvres           |
      | MW - Sniper and Maneater | Chrome Films | Mediawan    | 01/01/2023        | 01/01/2023 -      | Sniper, Maneater |