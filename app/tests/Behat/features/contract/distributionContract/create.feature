@distributionContract
Feature: Distribution contract create page

  Background:
    Given I load fixtures:
      | userCompany                       |
      | contract/distributionContractWork |
    And I am logged on
    And I am on "/distribution-contracts/add"

  Scenario: We check page title and redirection
    Then I should see "Ajouter un contrat à la société Haussmann Medias"
    When I follow "Retour"
    Then I should be on "/distribution-contracts"

  Scenario: We check form errors
    When I press "Enregistrer"
    Then I should see "Cette valeur ne doit pas être vide" 4 times

  Scenario: We check with valid data
    When I select "My Digital Company" from "distribution_contract_form[distributor]"
    When I select "France" from "distribution_contract_form[territories][]"
    When I additionally select "United States" from "distribution_contract_form[territories][]"
    When I select "AVOD" from "distribution_contract_form[broadcastChannels][]"
    When I additionally select "TVOD" from "distribution_contract_form[broadcastChannels][]"
    When I additionally select "Free TV" from "distribution_contract_form[broadcastChannels][]"
    And I fill in the following:
      | distribution_contract_form[name]                 | MDC - Winnie the Pooh               |
      | distribution_contract_form[type]                 | recurring                           |
      | distribution_contract_form[signedAt]             | 15/09/2023                          |
      | distribution_contract_form[reportFrequency]      | yearly                              |
      | distribution_contract_form[exclusivity]          | This contract is exclusive          |
      | distribution_contract_form[commercialConditions] | These are the commercial conditions |
    And I press "Enregistrer"
    Then I should be on "/distribution-contracts/3/works"
    And I should see "Contrat de sous-distribution MDC - Winnie the Pooh"
