@work
Feature: Work territory manage page

  Background:
    Given I load fixtures:
      | userCompany        |
      | work/workTerritory |
    And I am logged on
    And I am on "/works/1/territories/manage"

  Scenario: I check messages
    Then I should see "Gérer les territoires et canaux pour l’œuvre"
    And I should see a table with:
      | Territoire     | Exclusif | Sélectionner tout | AVOD | Free TV | SVOD | TVOD |
      | France         |          |                   |      |         |      |      |
      | United Kingdom |          |                   |      |         |      |      |
      | United States  |          |                   |      |         |      |      |

    # Exclusivity
    And the "work_territory_form[exclusives][territory_1]" checkbox should be checked
    And the "work_territory_form[exclusives][territory_2]" checkbox should be checked
    And the "work_territory_form[exclusives][territory_3]" checkbox should be checked

    # Broadcast channels
    And the "work_territory_form[broadcastChannels][territory_1_broadcast_channel_1]" checkbox should be checked
    And the "work_territory_form[broadcastChannels][territory_1_broadcast_channel_2]" checkbox should be checked
    And the "work_territory_form[broadcastChannels][territory_1_broadcast_channel_3]" checkbox should not be checked
    And the "work_territory_form[broadcastChannels][territory_1_broadcast_channel_4]" checkbox should not be checked

    And the "work_territory_form[broadcastChannels][territory_2_broadcast_channel_1]" checkbox should not be checked
    And the "work_territory_form[broadcastChannels][territory_2_broadcast_channel_2]" checkbox should not be checked
    And the "work_territory_form[broadcastChannels][territory_2_broadcast_channel_3]" checkbox should not be checked
    And the "work_territory_form[broadcastChannels][territory_2_broadcast_channel_4]" checkbox should not be checked

    And the "work_territory_form[broadcastChannels][territory_3_broadcast_channel_1]" checkbox should be checked
    And the "work_territory_form[broadcastChannels][territory_3_broadcast_channel_2]" checkbox should be checked
    And the "work_territory_form[broadcastChannels][territory_3_broadcast_channel_3]" checkbox should be checked
    And the "work_territory_form[broadcastChannels][territory_3_broadcast_channel_4]" checkbox should not be checked

    When I follow "Retour"
    Then I should be on "/works/1/territories"

  Scenario: I add United Stated territory
    When I uncheck "work_territory_form[exclusives][territory_2]"
    And I check "work_territory_form[broadcastChannels][territory_2_broadcast_channel_1]"
    And I check "work_territory_form[broadcastChannels][territory_2_broadcast_channel_2]"
    And I check "work_territory_form[broadcastChannels][territory_2_broadcast_channel_4]"
    And I press "Enregistrer"

    Then I should see "3 enregistrements trouvés"
    And I should see a table with:
      | Territoire     | Exclusif | Canaux              |
      | France         | true     | AVOD, SVOD          |
      | United Kingdom | true     | AVOD, SVOD, TVOD    |
      | United States  | false    | AVOD, Free TV, SVOD |

  Scenario: I remove France territory
    When I uncheck "work_territory_form[broadcastChannels][territory_1_broadcast_channel_1]"
    And I uncheck "work_territory_form[broadcastChannels][territory_1_broadcast_channel_2]"
    And I press "Enregistrer"

    Then I should see "1 enregistrements trouvés"
    And I should see a table with:
      | Territoire     | Exclusif | Canaux           |
      | United Kingdom | true     | AVOD, SVOD, TVOD |

  @javascript
  Scenario: I check select all row or column
    When I check "work_territory_form[selectAll][row][2]"
    Then the "work_territory_form[broadcastChannels][territory_2_broadcast_channel_1]" checkbox should be checked
    And the "work_territory_form[broadcastChannels][territory_2_broadcast_channel_2]" checkbox should be checked
    And the "work_territory_form[broadcastChannels][territory_2_broadcast_channel_3]" checkbox should be checked
    And the "work_territory_form[broadcastChannels][territory_2_broadcast_channel_4]" checkbox should be checked

    When I uncheck "work_territory_form[selectAll][row][2]"
    Then the "work_territory_form[broadcastChannels][territory_2_broadcast_channel_1]" checkbox should not be checked
    And the "work_territory_form[broadcastChannels][territory_2_broadcast_channel_2]" checkbox should not be checked
    And the "work_territory_form[broadcastChannels][territory_2_broadcast_channel_3]" checkbox should not be checked
    And the "work_territory_form[broadcastChannels][territory_2_broadcast_channel_4]" checkbox should not be checked

    Then the "work_territory_form[broadcastChannels][territory_1_broadcast_channel_4]" checkbox should not be checked
    And the "work_territory_form[broadcastChannels][territory_2_broadcast_channel_4]" checkbox should not be checked
    And the "work_territory_form[broadcastChannels][territory_3_broadcast_channel_4]" checkbox should not be checked

    When I check "work_territory_form[selectAll][column][4]"
    Then the "work_territory_form[broadcastChannels][territory_1_broadcast_channel_4]" checkbox should be checked
    And the "work_territory_form[broadcastChannels][territory_2_broadcast_channel_4]" checkbox should be checked
    And the "work_territory_form[broadcastChannels][territory_3_broadcast_channel_4]" checkbox should be checked