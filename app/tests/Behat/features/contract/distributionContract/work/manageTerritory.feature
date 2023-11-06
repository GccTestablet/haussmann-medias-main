@distributionContract @work
Feature: Distribution contract work territory manage page

  Background:
    Given I load fixtures:
      | userCompany                                |
      | contract/distributionContractWorkTerritory |
      | work/workTerritory                         |
    And I am logged on
    And I am on "/distribution-contracts/1/work/1/territories/manage"

  Scenario: I check messages
    Then I should see "Gérer les territoires et canaux pour l’œuvre"
    And I should see a table with:
      | Territoire     | Exclusif | Sélectionner tout | AVOD | SVOD | TVOD       |
      | France         |          |                   |      |      | Non acquis |
      | United Kingdom |          |                   |      |      |            |

    # Exclusivity
    And the "distribution_contract_work_territory_form[exclusives][territory_1]" checkbox should be checked
    And the "distribution_contract_work_territory_form[exclusives][territory_3]" checkbox should be checked

    # Broadcast channels
    And the "distribution_contract_work_territory_form[broadcastChannels][territory_1_broadcast_channel_1]" checkbox should be checked
    And the "distribution_contract_work_territory_form[broadcastChannels][territory_1_broadcast_channel_2]" checkbox should be checked

    And the "distribution_contract_work_territory_form[broadcastChannels][territory_3_broadcast_channel_1]" checkbox should be checked
    And the "distribution_contract_work_territory_form[broadcastChannels][territory_3_broadcast_channel_2]" checkbox should be checked
    And the "distribution_contract_work_territory_form[broadcastChannels][territory_3_broadcast_channel_3]" checkbox should not be checked

    When I follow "Retour"
    Then I should be on "/distribution-contracts/1/works"

  @javascript
  Scenario: I update territories
    When I uncheck "distribution_contract_work_territory_form[exclusives][territory_1]"
    And I check "distribution_contract_work_territory_form[broadcastChannels][territory_3_broadcast_channel_3]"
    And I press "Enregistrer"
    Then I should be on "/distribution-contracts/1/works"
    When I click on "[data-work-id='1']"
    Then I should see "Territoires et canaux distribués"
    And I should see a table "#work-1-territories-table" with:
      | Territoire     | Exclusif | Canaux           |
      | France         | false    | AVOD, SVOD       |
      | United Kingdom | true     | AVOD, SVOD, TVOD |

  @javascript
  Scenario: I remove territory
    And I uncheck "distribution_contract_work_territory_form[broadcastChannels][territory_3_broadcast_channel_1]"
    And I uncheck "distribution_contract_work_territory_form[broadcastChannels][territory_3_broadcast_channel_2]"
    And I uncheck "distribution_contract_work_territory_form[broadcastChannels][territory_3_broadcast_channel_3]"
    And I press "Enregistrer"
    Then I should be on "/distribution-contracts/1/works"
    When I click on "[data-work-id='1']"
    Then I should see "Territoires et canaux distribués"
    And I should see a table "#work-1-territories-table" with:
      | Territoire |
      | France     |
    And I should not see a table "#work-1-territories-table" with:
      | Territoire     |
      | United Kingdom |

  @javascript
  Scenario: I check select all row or column
    When I check "distribution_contract_work_territory_form[selectAll][row][3]"
    Then the "distribution_contract_work_territory_form[broadcastChannels][territory_3_broadcast_channel_1]" checkbox should be checked
    And the "distribution_contract_work_territory_form[broadcastChannels][territory_3_broadcast_channel_2]" checkbox should be checked
    And the "distribution_contract_work_territory_form[broadcastChannels][territory_3_broadcast_channel_3]" checkbox should be checked

    When I uncheck "distribution_contract_work_territory_form[selectAll][row][3]"
    Then the "distribution_contract_work_territory_form[broadcastChannels][territory_3_broadcast_channel_1]" checkbox should not be checked
    And the "distribution_contract_work_territory_form[broadcastChannels][territory_3_broadcast_channel_2]" checkbox should not be checked
    And the "distribution_contract_work_territory_form[broadcastChannels][territory_3_broadcast_channel_3]" checkbox should not be checked

    When I uncheck "distribution_contract_work_territory_form[selectAll][column][1]"
    Then the "distribution_contract_work_territory_form[broadcastChannels][territory_1_broadcast_channel_1]" checkbox should not be checked
    Then the "distribution_contract_work_territory_form[broadcastChannels][territory_3_broadcast_channel_1]" checkbox should not be checked

    When I check "distribution_contract_work_territory_form[selectAll][column][1]"
    Then the "distribution_contract_work_territory_form[broadcastChannels][territory_1_broadcast_channel_1]" checkbox should be checked
    Then the "distribution_contract_work_territory_form[broadcastChannels][territory_3_broadcast_channel_1]" checkbox should be checked