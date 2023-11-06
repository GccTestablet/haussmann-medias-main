@acquisitionContract @work
Feature: Acquisition contract work create page

  Background:
    Given I load fixtures:
      | userCompany                  |
      | contract/acquisitionContract |
      | work/work                    |
    And I am logged on
    And I am on "/acquisition-contracts/1/works/add"

  Scenario: I check form errors
    When I press "Enregistrer"
    Then I should see "Cette valeur ne doit pas être vide" 5 times
    When I follow "Retour"
    Then I should be on "/acquisition-contracts/1"

  Scenario: I create a new work
    When I fill in "work_form[imdbId]" with "tt5433140"
    And I fill in "work_form[originalName]" with "Fast & Furious 10"
    And I fill in "work_form[name]" with "Fast X"
    And I select "US" from "work_form[countries][]"
    And I select "international" from "work_form[quota]"
    And I fill in "work_form[year]" with "2023"
    And I fill in "work_form[duration]" with "141"
    And I fill in "work_form[minimumGuaranteedBeforeReversion]" with "15000"
    And I fill in "work_form[minimumCostOfTheTopBeforeReversion]" with "10000"
    And I select "USD" from "work_form[currency]"
    And I press "Enregistrer"
    Then I should be on "/works/5/territories"
    And I should see "Contrat HF - Winnie the Pooh"
    And I should see "Ayant droit HKA Films"
    And I should see "ID interne HAU000003"
    And I should see "IMDB tt5433140"
    And I should see "Titre français Fast X"
    And I should see "Titre original Fast & Furious 10"
    And I should see "Minimum garanti 15 000,00 $US"
    And I should see "Plafond des frais recoupables 10 000,00 $US"
    And I should see "Année de production 2023"
    And I should see "Durée 141"
    And I should see "Pays d'origine États-Unis"
    And I should see "Quota Étranger"


