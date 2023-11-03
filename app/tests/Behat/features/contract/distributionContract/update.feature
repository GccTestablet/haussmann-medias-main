@distributionContract
Feature: Distribution contract update page

  Background:
    Given I load fixtures:
      | userCompany                       |
      | contract/distributionContractWork |
    And I am logged on
    And I am on "/distribution-contracts/1/update"

  Scenario: We check page title and redirection
    Then I should see "Mettre à jour le contrat MW - Winnie the Pooh de la société Haussmann Medias"
    When I follow "Retour"
    Then I should be on "/distribution-contracts"

  Scenario: We check with valid data
    When I select "Chrome Films" from "distribution_contract_form[distributor]"
    And I fill in the following:
      | distribution_contract_form[name] | CF - Winnie Pooh |
    And I press "Enregistrer"
    Then I should be on "/distribution-contracts/1/works"
    And I should see "CF - Winnie Pooh"
