@deprecated @javascript
Feature: Execute a job
  In order to use existing product information
  As a product manager
  I need to be able to import products

  Background:
    Given the "footwear" catalog configuration
    And the following product groups:
      | code  | label     | axis | type    |
      | CROSS | Bag Cross |      | VARIANT |
    And I am logged in as "Julia"

  Scenario: Successfully import a csv file of products
    Given the following CSV file to import:
      """
      sku;family;groups;categories;name-en_US;description-en_US-tablet
      SKU-001;boots;CROSS;winter_boots;Donec;dictum magna. Ut tincidunt orci quis lectus. Nullam suscipit, est
      SKU-002;sneakers;;winter_boots;Donex;Pellentesque habitant morbi tristique senectus et netus et malesuada fames
      SKU-003;sneakers;;sandals;ac;Morbi quis urna. Nunc quis arcu vel quam dignissim pharetra.
      SKU-004;sneakers;;sandals;nec;justo sit amet nulla. Donec non justo. Proin non massa
      SKU-005;boots;CROSS;winter_boots,sandals;non;tincidunt dui augue eu tellus. Phasellus elit pede, malesuada vel
      SKU-006;boots;CROSS;winter_boots;ipsum;Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam auctor,
      SKU-007;sneakers;;;rutrum.;quis, pede. Praesent eu dui. Cum sociis natoque penatibus et
      SKU-008;boots;CROSS;winter_boots;ligula;urna et arcu imperdiet ullamcorper. Duis at lacus. Quisque purus
      SKU-009;sneakers;;;porttitor;sagittis. Duis gravida. Praesent eu nulla at sem molestie sodales.
      SKU-010;boots;CROSS;sandals;non,;vestibulum nec, euismod in, dolor. Fusce feugiat. Lorem ipsum dolor
      """
    And the following job "footwear_product_import" configuration:
      | filePath | %file to import% |
    When I am on the "footwear_product_import" import job page
    And I launch the import job
    And I wait for the "footwear_product_import" job to finish
    Then there should be 10 products
    And the family of the product "SKU-006" should be "boots"
    And product "SKU-007" should be enabled
    And the english tablet name of "SKU-001" should be "Donec"
    And the english tablet description of "SKU-002" should be "Pellentesque habitant morbi tristique senectus et netus et malesuada fames"

  @pim-2445
  Scenario: Successfully skip associations of invalid product
    Given the following CSV file to import:
      """
      sku;family;groups;categories;X_SELL-groups;X_SELL-products;name-en_US;description-en_US-tablet
      SKU-001;boots;CROSS;unknown,travel;CROSS;SKU-002,SKU-003;Donec;dictum magna. Ut tincidunt orci quis lectus. Nullam suscipit, est
      """
    And the following job "footwear_product_import" configuration:
      | filePath | %file to import% |
    When I am on the "footwear_product_import" import job page
    And I launch the import job
    And I wait for the "footwear_product_import" job to finish
    Then there should be 0 product

  Scenario: Successfully ignore duplicate unique data
    Given the following CSV file to import:
      """
      sku;family;groups;categories;name-en_US;description-en_US-tablet
      SKU-001;boots;;winter_boots;Donec;dictum magna. Ut tincidunt orci quis lectus. Nullam suscipit, est
      SKU-001;sneakers;;winter_boots;Donex;Pellentesque habitant morbi tristique senectus et netus et malesuada fames
      """
    And the following job "footwear_product_import" configuration:
      | filePath | %file to import% |
    When I am on the "footwear_product_import" import job page
    And I launch the import job
    And I wait for the "footwear_product_import" job to finish
    Then I should see "The unique code \"SKU-001\" was already read in this file"
    Then there should be 1 product
    And the english tablet name of "SKU-001" should be "Donec"
    And the english tablet description of "SKU-001" should be "dictum magna. Ut tincidunt orci quis lectus. Nullam suscipit, est"

  Scenario: Successfully update an existing product
    Given the following product:
      | sku     | name-en_US |
      | SKU-001 | FooBar     |
    And the following CSV file to import:
      """
      sku;family;groups;categories;name-en_US;description-en_US-tablet
      SKU-001;boots;;winter_boots;Donec;dictum magna. Ut tincidunt orci quis lectus. Nullam suscipit, est
      """
    And the following job "footwear_product_import" configuration:
      | filePath | %file to import% |
    When I am on the "footwear_product_import" import job page
    And I launch the import job
    And I wait for the "footwear_product_import" job to finish
    Then there should be 1 product
    And the english tablet name of "SKU-001" should be "Donec"
    And the english tablet description of "SKU-001" should be "dictum magna. Ut tincidunt orci quis lectus. Nullam suscipit, est"

  Scenario: Successfully import products through file upload
    Given the following CSV file to import:
      """
      sku;family;groups;categories;name-en_US;description-en_US-tablet
      SKU-001;boots;;winter_boots;Donec;dictum magna. Ut tincidunt orci quis lectus. Nullam suscipit, est
      SKU-002;sneakers;;winter_boots;Donex;Pellentesque habitant morbi tristique senectus et netus et malesuada fames
      SKU-003;sneakers;;sandals;ac;Morbi quis urna. Nunc quis arcu vel quam dignissim pharetra.
      SKU-004;sneakers;;sandals;nec;justo sit amet nulla. Donec non justo. Proin non massa
      SKU-005;boots;;winter_boots;non;tincidunt dui augue eu tellus. Phasellus elit pede, malesuada vel
      SKU-006;boots;;winter_boots;ipsum;Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam auctor,
      SKU-007;sneakers;;;rutrum.;quis, pede. Praesent eu dui. Cum sociis natoque penatibus et
      SKU-008;boots;;sandals;ligula;urna et arcu imperdiet ullamcorper. Duis at lacus. Quisque purus
      SKU-009;sneakers;;;porttitor;sagittis. Duis gravida. Praesent eu nulla at sem molestie sodales.
      SKU-010;boots;;sandals,winter_boots;non,;vestibulum nec, euismod in, dolor. Fusce feugiat. Lorem ipsum dolor
      """
    And the following job "footwear_product_import" configuration:
      | uploadAllowed | yes |
    When I am on the "footwear_product_import" import job page
    And I upload and import the file "%file to import%"
    And I wait for the "footwear_product_import" job to finish
    Then there should be 10 products

  Scenario: Successfully import products prices
    Given the following CSV file to import:
      """
      sku;price
      SKU-001;"100 EUR, 90 USD"
      SKU-002;50 EUR
      """
    And the following job "footwear_product_import" configuration:
      | filePath | %file to import% |
    When I am on the "footwear_product_import" import job page
    And I launch the import job
    And I wait for the "footwear_product_import" job to finish
    Then there should be 2 products
    And the product "SKU-001" should have the following value:
      | price | 100.00 EUR, 90.00 USD |
    And the product "SKU-002" should have the following value:
      | price | 50.00 EUR |

  Scenario: Successfully update existing products prices
    Given the following product:
      | sku     | price            |
      | SKU-001 | 100 EUR, 150 USD |
    And the following CSV file to import:
      """
      sku;price
      SKU-001;"100 EUR, 90 USD"
      """
    And the following job "footwear_product_import" configuration:
      | filePath | %file to import% |
    When I am on the "footwear_product_import" import job page
    And I launch the import job
    And I wait for the "footwear_product_import" job to finish
    Then there should be 1 products
    And the product "SKU-001" should have the following value:
      | price | 100.00 EUR, 90.00 USD |

  Scenario: Successfully import products metrics
    Given the following CSV file to import:
      """
      sku;length
      SKU-001;4000 CENTIMETER
      """
    And the following job "footwear_product_import" configuration:
      | filePath | %file to import% |
    When I am on the "footwear_product_import" import job page
    And I launch the import job
    And I wait for the "footwear_product_import" job to finish
    Then there should be 1 products
    And the product "SKU-001" should have the following value:
      | length | 4000.0000 CENTIMETER |

  Scenario: Successfully import products metrics splitting the data and unit
    Given the following CSV file to import:
      """
      sku;length;length-unit
      SKU-001;4000;CENTIMETER
      """
    And the following job "footwear_product_import" configuration:
      | filePath | %file to import% |
    When I am on the "footwear_product_import" import job page
    And I launch the import job
    And I wait for the "footwear_product_import" job to finish
    Then there should be 1 products
    And the product "SKU-001" should have the following value:
      | length | 4000.0000 CENTIMETER |

  @jira https://akeneo.atlassian.net/browse/PIM-3377
  Scenario: Fail when import invalid attribute with nonexistent specific locale
    Given the following attributes:
      | code                      | type | localizable | availableLocales |
      | locale_specific_attribute | text | yes         | en_US            |
    Then I am on the Attribute index page
    And I add the "french" locale to the "mobile" channel
    Given the following CSV file to import:
      """
      sku;locale_specific_attribute-fr_FR
      SKU-001;test value
      """
    And the following job "footwear_product_import" configuration:
      | filePath | %file to import% |
    When I am on the "footwear_product_import" import job page
    And I launch the import job
    And I wait for the "footwear_product_import" job to finish
    Then I should see "The provided specific locale \"fr_FR\" does not exist for \"locale_specific_attribute\" attribute"
