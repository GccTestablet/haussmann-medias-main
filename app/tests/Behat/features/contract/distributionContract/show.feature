@distributionContract
Feature: Distribution contract show page

  Background:
    Given I load fixtures:
      | userCompany                                |
      | contract/distributionContractWorkTerritory |
      | contract/distributionContractWorkRevenue   |
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
    Then I should see "Ajouter une œuvre"
    And I should see a table with:
      | Œuvre                       | Période                 | Montant     |
      | Winnie the Pooh (HAU000001) | 01/01/2023 - 31/12/2023 | 50 000,00 € |
    When I click on "[data-work-id='1']"
    Then I wait to see "Territoires et canaux distribués"
    And I should see a table "#work-1-territories-table" with:
      | Territoire     | Exclusif | Canaux     |
      | France         | true     | AVOD, SVOD |
      | United Kingdom | true     | AVOD, SVOD |
    When I click "Modifier" on the row containing "Winnie the Pooh (HAU000001)"
    Then I should be on "/distribution-contracts/1/works/1/update"
    When I am on "/distribution-contracts/1"
    And I click "Gérer les territoires" on the row containing "Winnie the Pooh (HAU000001)"
    Then I should be on "/distribution-contracts/1/work/1/territories/manage"

  Scenario: We check the revenues list
    When I follow "Revenus"
    Then I should be on "/distribution-contracts/1/revenues"
    Then I should see "Télécharger le modèle CSV"
    And I should see "Importer le CSV des revenus"
    And I should see "Afficher les filtres"
    And I should see "6 enregistrements trouvés"
    And I should see a table with:
      | Œuvre           | Canal | Période                 | Montant    |
      | Winnie the Pooh | AVOD  | 01/07/2023 - 30/09/2023 | 400,00 $US |
      | Winnie the Pooh | SVOD  | 01/07/2023 - 30/09/2023 | 450,50 $US |
      | Winnie the Pooh | AVOD  | 01/04/2023 - 30/06/2023 | 800,00 €   |
      | Winnie the Pooh | SVOD  | 01/04/2023 - 30/06/2023 | 1 200,00 € |
      | Winnie the Pooh | AVOD  | 01/01/2023 - 31/03/2023 | 2 300,00 € |
      | Winnie the Pooh | SVOD  | 01/01/2023 - 31/03/2023 | 800,00 €   |
    And I should see "5 100,00 €"
    And I should see "850,50 $US"
    When I follow "Télécharger le modèle CSV"
    Then the file "Modèle contrat Mediawan.csv" should be downloaded
    And the downloaded file should contains
    """
    ID;NAME;AVOD;SVOD;TVOD
    HAU000001;"Winnie the Pooh";;;N.A.
    """
    When I am on "/distribution-contracts/1/revenues"
    And I follow "Importer le CSV des revenus"
    Then I should be on "/distribution-contracts/1/work/revenues/import"