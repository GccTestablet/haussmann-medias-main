@layout
Feature: Menu redirections

  Background:
    Given I load fixtures:
      | userCompany |
    And I am logged on

  Scenario: Redirect to the company page
    When I follow "Contrats d'acquisition"
    Then I should be on "/acquisition-contracts"

    When I follow "Contrats de sous-distribution"
    Then I should be on "/distribution-contracts"

    When I follow "Œuvres"
    Then I should be on "/works"

    When I follow "Sociétés"
    Then I should be on "/companies"

    When I follow "Utilisateurs"
    Then I should be on "/users"

    When I follow "Canaux de diffusion"
    Then I should be on "/settings/broadcast/channels"

    When I follow "Territoires"
    Then I should be on "/settings/territories"

    When I follow "Types de coûts de distribution"
    Then I should be on "/settings/adaptation-cost-types"