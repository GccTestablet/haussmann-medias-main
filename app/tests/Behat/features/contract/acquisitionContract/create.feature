@acquisitionContract
Feature: Acquisition contract create page

  Background:
    Given I load fixtures:
      | userCompany                  |
      | contract/acquisitionContract |
      | work/work                    |
    And I am logged on
    And I am on "/acquisition-contracts/add"

  Scenario: We check page title and redirection
    Then I should see "Ajouter un contrat à la société Haussmann Medias"
    When I follow "Retour"
    Then I should be on "/acquisition-contracts"

  Scenario: We check form errors
    When I press "Enregistrer"
    Then I should see "Cette valeur ne doit pas être vide" 3 times

  Scenario: We check with valid data
    When I select "HKA Films" from "acquisition_contract_form[beneficiary]"
    And I fill in the following:
      | acquisition_contract_form[name]     | HKF - Fast And Furious 3 |
      | acquisition_contract_form[signedAt] | 01/05/2023               |
    And I press "Enregistrer"
    Then I should be on "/acquisition-contracts/3"
    And I should see "HKF - Fast And Furious 3"
