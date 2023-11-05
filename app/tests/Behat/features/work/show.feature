@work
Feature: Work show page

  Background:
    Given I load fixtures:
      | userCompany         |
      | work/workTerritory  |
      | work/workAdaptation |
      | work/workReversion  |
    And I am logged on
    And I am on "/works/1"

  Scenario: I can see the work details
    Then I should see "Winnie the Pooh"
    And I should see "Contrat HF - Winnie the Pooh"
    And I should see "Ayant droit HKA Films"
    And I should see "ID interne HAU000001"
    And I should see "IMDB tt19623240"
    And I should see "Titre français Winnie the Pooh"
    And I should see "Titre original Winnie the Pooh: Blood and Honey"
    And I should see "Minimum garanti"
    And I should see "Plafond des frais recoupables"
    And I should see "Année de production"
    And I should see "Pays d'origine Royaume-Uni"
    And I should see "Quota Européen"

  Scenario: I check territories and channels tab
    Then I should see "Territoires et canaux acquis"
    And I should see "2 enregistrements trouvés"
    And I should see a table with:
      | Territoire     | Exclusif | Canaux           |
      | France         | true     | AVOD, SVOD       |
      | United Kingdom | true     | AVOD, SVOD, TVOD |
    When I follow "Gérer les territoires et les canaux"
    Then I should be on "/works/1/territories/manage"

  Scenario: I check distribution cost tab
    When I follow "Coûts de distribution"
    Then I should be on "/works/1/distribution-costs"
    And I should see "2 enregistrements trouvés"
    And I should see a table with:
      | Type            | Montant    | Commentaire |
      | Dubbing cost    | 2 300,00 € |             |
      | Subtitling cost | 500,00 €   |             |
    When I follow "Ajouter un coût"
    Then I should be on "/works/1/distribution-costs/add"
    And I am on "/works/1/distribution-costs"
    When I click "Modifier" on the row containing "Dubbing cost"
    Then I should be on "/works/1/distribution-costs/1/update"

  Scenario: I check reversions tab
    When I follow "Reversements"
    Then I should be on "/works/1/reversions"
    And I should see "3 enregistrements trouvés"
    And I should see a table with:
      | Canal | Pourcentage ayant-droit |
      | AVOD  | 50                      |
      | SVOD  | 20                      |
      | TVOD  | 80                      |
    When I follow "Gérer les reversements"
    Then I should be on "/works/1/reversions/manage"