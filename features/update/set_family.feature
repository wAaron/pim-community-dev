Feature: Update family fields
  In order to update products
  As an internal process or any user
  I need to be able to update the family field of a product

  Scenario: Successfully update the family field
    Given a "default" catalog configuration
    And the following products:
      | sku                              |
      | tshirt1                          |
      | tshirt2                          |
    And the following families:
      | code  |
      | shirt |
      | mug   |
      | tv    |
    Then I should get the following products after apply the following updater to it:
      | product | actions                                                                                                               | result              |
      | tshirt1 | [{"type": "set_value", "field": "family", "value": "shirt"}]                                                          | {"family": "shirt"} |
      | tshirt1 | [{"type": "set_value", "field": "family", "value": null}]                                                             | {"family": null}    |
      | tshirt2 | [{"type": "set_value", "field": "family", "value": "mug"}]                                                            | {"family": "mug"}   |
      | tshirt2 | [{"type": "set_value", "field": "family", "value": "shirt"}, {"type": "set_value", "field": "family", "value": "tv"}] | {"family": "tv"}    |