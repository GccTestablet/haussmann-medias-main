@work
Feature: Work show page

  Background:
    Given I load fixtures:
      | userCompany         |
      | work/workTerritory  |
      | work/workAdaptation |
      | work/workReversion  |
    And I am logged on
    And I am on "/works/1/update"

  Scenario: We check page title and redirection
    Then I should see "Mettre à jour l'œuvre Winnie the Pooh"
    When I follow "Retour"
    Then I should be on "/works/1/territories"

  Scenario: We check with valid data
    When I fill in "work_form[imdbId]" with "tt3665498"
    And I fill in "work_form[originalName]" with "Beyond the Sky"
    And I fill in "work_form[name]" with "Beyond the Sky"
    And I select "US" from "work_form[countries][]"
    And I select "international" from "work_form[quota]"
    And I fill in "work_form[year]" with "2018"
    And I fill in "work_form[duration]" with "82"
    And I fill in "work_form[minimumGuaranteedBeforeReversion]" with "5000"
    And I fill in "work_form[minimumCostOfTheTopBeforeReversion]" with "3000"
    And I select "USD" from "work_form[currency]"
    And I press "Enregistrer"
    Then I should be on "/works/1/territories"
    And I should see "Contrat HF - Winnie the Pooh"
    And I should see "Ayant droit HKA Films"
    And I should see "ID interne HAU000001"
    And I should see "IMDB tt3665498"
    And I should see "Titre français Beyond the Sky"
    And I should see "Titre original Beyond the Sky"
    And I should see "Minimum garanti 5 000,00 $US"
    And I should see "Plafond des frais recoupables 3 000,00 $US"
    And I should see "Année de production 2018"
    And I should see "Durée 82"
    And I should see "Pays d'origine États-Unis"
    And I should see "Quota Étranger"