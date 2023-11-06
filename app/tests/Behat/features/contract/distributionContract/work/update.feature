@distributionContract @work
Feature: Distribution contract work update page

  Background:
    Given I load fixtures:
      | userCompany                              |
      | contract/distributionContractWorkRevenue |
      | work/workTerritory                       |
    And I am logged on
    And I am on "/distribution-contracts/1/works/1/update"

  Scenario: We check page title and redirection
    Then I should see "Modifier l'œuvre Winnie the Pooh du contrat MW - Winnie the Pooh"
    And the "distribution_contract_work_form[work]" select field should contain:
      | Winnie the Pooh (HAU000001) |
      | Hurricane (HAU000002)       |
    And the option "Winnie the Pooh (HAU000001)" from select "distribution_contract_work_form[work]" should be selected
    When I follow "Retour"
    Then I should be on "/distribution-contracts/1/works"

  Scenario: I update the work
    When I fill in "distribution_contract_work_form[startsAt]" with "01/01/2022"
    And I fill in "distribution_contract_work_form[endsAt]" with "31/12/2022"
    And I fill in "distribution_contract_work_form[amount]" with "10000"
    And I select "USD" from "distribution_contract_work_form[currency]"
    And I press "Enregistrer"
    Then I should be on "/distribution-contracts/1/works"
    And I should see a table with:
      | Œuvre                       | Période                 | Montant       |
      | Winnie the Pooh (HAU000001) | 01/01/2022 - 31/12/2022 | 10 000,00 $US |