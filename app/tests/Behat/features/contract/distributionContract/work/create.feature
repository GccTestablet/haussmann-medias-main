@distributionContract @work
Feature: Distribution contract work add page

  Background:
    Given I load fixtures:
      | userCompany                                |
      | contract/distributionContractWorkTerritory |
      | work/workTerritory                         |
    And I am logged on
    And I am on "/distribution-contracts/1/works/add"

  Scenario: We check page title and redirection
    Then I should see "Ajouter une œuvre au contrat de sous-distribution MW - Winnie the Pooh"
    And the "distribution_contract_work_form[work]" select field should contain:
      | Hurricane (HAU000002) |
    And the "distribution_contract_work_form[work]" select field should not contain:
      | Winnie the Pooh (HAU000001) |
    When I follow "Retour"
    Then I should be on "/distribution-contracts/1/works"

  Scenario: I add a work
    When I select "Hurricane (HAU000002)" from "distribution_contract_work_form[work]"
    And I fill in "distribution_contract_work_form[startsAt]" with "01/01/2019"
    And I fill in "distribution_contract_work_form[endsAt]" with "31/12/2019"
    And I fill in "distribution_contract_work_form[amount]" with "5500.50"
    And I select "EUR" from "distribution_contract_work_form[currency]"
    And I press "Enregistrer"
    Then I should be on "/distribution-contracts/1/works"
    And I should see a table with:
      | Œuvre                       | Période                 | Montant     |
      | Winnie the Pooh (HAU000001) | 01/01/2023 - 31/12/2023 | 50 000,00 € |
      | Hurricane (HAU000002)       | 01/01/2019 - 31/12/2019 | 5 500,50 €  |