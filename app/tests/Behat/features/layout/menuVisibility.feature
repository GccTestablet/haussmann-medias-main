@layout
Feature: Menu visibility

  Background:
    Given I load fixtures:
      | userCompany |

  Scenario: Menu visibility as Super admin
    Given I am logged on as "super-admin@hm.mail"
    Then I should see "Contrats d'acquisition"
    And I should see "Contrats de sous-distribution"
    And I should see "Œuvres"
    And I should see "Sociétés"
    And I should see "Utilisateurs"
    And I should see "Canaux de diffusion"
    And I should see "Territoires"
    And I should see "Types de coûts de distribution"

  Scenario: Menu visibility as simple user
    Given I am logged on as "simple-user@hm.mail"
    Then I should see "Contrats d'acquisition"
    And I should see "Contrats de sous-distribution"
    And I should see "Œuvres"
    And I should not see "Sociétés"
    And I should not see "Utilisateurs"
    And I should not see "Canaux de diffusion"
    And I should not see "Territoires"
    And I should not see "Types de coûts de distribution"