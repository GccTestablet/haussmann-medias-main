@distributionContract @revenues
Feature: Distribution contract show page

  Background:
    Given I load fixtures:
      | userCompany                                |
      | contract/distributionContractWorkTerritory |
      | contract/distributionContractWorkRevenue   |
    And I am logged on
    And I am on "/distribution-contracts/1/work/revenues/import"

  Scenario: We check page title and redirection
    Then I should see "Importer les revenus au contrat de sous-distribution MW - Winnie the Pooh"
    When I follow "Retour"
    Then I should be on "/distribution-contracts/1/revenues"

  Scenario: We check form errors
    When I press "Enregistrer"
    Then I should see "Cette valeur ne doit pas être vide" 3 times

    Scenario: We import revenues
      When I fill in "Début" with "01/01/2018"
      And I fill in "Fin" with "31/12/2018"
      And I attach the file "documents/distribution_contract_revenues.csv" to "Fichier"
      And I select "USD" from "Devise"
      And I press "Enregistrer"
      Then I should be on "distribution-contracts/1/revenues"
      And I should see "8 enregistrements trouvés"
      And I should see a table with:
        | Œuvre           | Canal | Période                 | Montant     |
        | Winnie the Pooh | AVOD  | 01/01/2018 - 31/12/2018 | 500,00 $US  |
        | Winnie the Pooh | SVOD  | 01/01/2018 - 31/12/2018 | -300,00 $US |
      And I should see "5 100,00 €"
      And I should see "1 050,50 $US"