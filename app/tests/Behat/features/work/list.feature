@work
Feature: Work list page

  Background:
    Given I load fixtures:
      | userCompany |
      | work/work   |
    And I am logged on
    And I am on "/works"

  Scenario: I check if links are working as expected
    When I follow "HAU000001"
    Then I should be on "/works/1/territories"

    When I am on "/works"
    And I follow "HF - Winnie the Pooh"
    Then I should be on "/acquisition-contracts/1"

    When I am on "/works"
    And I follow "Modifier"
    Then I should be on "/works/1/update"

  Scenario: I check if datatable has expected rows
    Then I should see "Liste des œuvres"
    And I should see "Afficher les filtres"
    And I should see "1 enregistrements trouvés"
    And I should see a table with:
      | ID interne | Titre français  | Pays d'origine | Contrat d'acquisition |
      | HAU000001  | Winnie the Pooh | Royaume-Uni    | HF - Winnie the Pooh  |
    When I switch to company "Chrome Films"
    Then I should see "2 enregistrements trouvés"
    And I should see a table with:
      | ID interne | Titre français | Pays d'origine | Contrat d'acquisition   |
      | CHR000002  | Maneater       | France         | MW - Sniper and Mane... |
      | CHR000001  | Sniper         | États-Unis     | MW - Sniper and Mane... |

  @javascript
  Scenario: I check if filters are working as expected
    When I update entity "App\Entity\User" with ID "1":
      | field       | value                | type      |
      | connectedOn | company.chrome_films | reference |
    And I reload the page
    And I press "Afficher les filtres"
    Then I should see "ID interne"
    And I should see "ID IMDB"
    And I should see "Titre (Français ou original)"
    And I should see "Quotas"
    And I should see "Pays"
    And I should see "Nom du contrat d'acquisition"
    And I should see "Ayant-droit du contrat d'acquisition"
    And I should see "Territoires acquis"
    And I should see "Territoires sous-distribués"
    And I should see "Afficher les archives ?"
    And I should see "Rechercher"
    And I should see "Réinitialiser"
    When I fill in "ID interne" with "1"
    And I press "Rechercher"
    Then I should see "1 enregistrements trouvés"
    And I should see a table with:
      | ID interne | Titre français | Pays d'origine | Contrat d'acquisition   |
      | CHR000001  | Sniper         | États-Unis     | MW - Sniper and Mane... |
    When I press "Réinitialiser"
    Then I should see "2 enregistrements trouvés"
    When I select "France" from "work_pager_form[countries][]"
    And I press "Rechercher"
    Then I should see "1 enregistrements trouvés"
    And I should see a table with:
      | ID interne | Titre français | Pays d'origine | Contrat d'acquisition   |
      | CHR000002  | Maneater       | France         | MW - Sniper and Mane... |
    When I select "Étranger" from "work_pager_form[quotas][]"
    And I press "Rechercher"
    Then I should see "0 enregistrements trouvés"
    When I press "Réinitialiser"
    When I select "Étranger" from "work_pager_form[quotas][]"
    And I press "Rechercher"
    Then I should see "1 enregistrements trouvés"
    And I should see a table with:
      | ID interne | Titre français | Pays d'origine | Contrat d'acquisition   |
      | CHR000001  | Sniper         | États-Unis     | MW - Sniper and Mane... |