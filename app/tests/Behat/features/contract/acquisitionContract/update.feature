@acquisitionContract
Feature: Acquisition contract update page

  Background:
    Given I load fixtures:
      | userCompany                  |
      | contract/acquisitionContract |
      | work/work                    |
    And I am logged on
    And I am on "/acquisition-contracts/1/update"

  Scenario: We check page title and redirection
    Then I should see "Mettre à jour le contrat HF - Winnie the Pooh de la société Haussmann Medias"
    When I follow "Retour"
    Then I should be on "/acquisition-contracts"

  Scenario: We check with valid data
    When I select "Haussmann Medias" from "acquisition_contract_form[beneficiary]"
    And I fill in the following:
      | acquisition_contract_form[name] | HM - Winnie Pooh |
    And I press "Enregistrer"
    Then I should be on "/acquisition-contracts/1"
    And I should see "HM - Winnie Pooh"
