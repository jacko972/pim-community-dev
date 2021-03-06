@javascript
Feature: Classify a product
  In order to classify products
  As a product manager
  I need to associate a product to categories

  Background:
    Given the "footwear" catalog configuration
    And the following products:
      | sku    |
      | tea    |
      | coffee |
    And I am logged in as "Julia"

  Scenario: Associate a product to categories
    Given I edit the "tea" product
    When I visit the "Categories" tab
    And I select the "2014 collection" tree
    And I expand the "2014 collection" category
    And I click on the "Summer collection" category
    And I click on the "Winter collection" category
    And I press the "Save" button
    Then the categories of "tea" should be "summer_collection and winter_collection"
