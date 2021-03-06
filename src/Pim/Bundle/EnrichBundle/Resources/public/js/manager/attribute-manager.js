'use strict';

define([
        'jquery',
        'underscore',
        'pim/fetcher-registry'
    ], function (
        $,
        _,
        FetcherRegistry
    ) {
        return {
            /**
             * Get the attributes of the given product
             * @param  Object product
             *
             * @return Array
             */
            getAttributesForProduct: function (product) {
                if (!product.family) {
                    return $.Deferred().resolve(_.keys(product.values));
                } else {
                    return FetcherRegistry.getFetcher('family')
                        .fetch(product.family)
                        .then(function (family) {
                            return _.union(_.keys(product.values), family.attributes);
                        });
                }
            },

            /**
             * Get all optional attributes available for a product
             * @param Object product
             *
             * @return Array
             */
            getAvailableOptionalAttributes: function (product) {
                return $.when(
                    FetcherRegistry.getFetcher('attribute').fetchAll(),
                    this.getAttributesForProduct(product)
                ).then(function (attributes, productAttributes) {
                    var optionalAttributes = _.map(
                        _.difference(_.pluck(attributes, 'code'), productAttributes),
                        function (attributeCode) {
                            return _.findWhere(attributes, {code: attributeCode});
                        }
                    );

                    return optionalAttributes;
                });
            },

            /**
             * Check if an attribute is optional
             * @param Object attribute
             * @param Object product
             * @param Array  families
             *
             * @return boolean
             */
            isOptional: function (attribute, product, families) {
                return 'pim_catalog_identifier' !== attribute.type &&
                    (!product.family ? true : !_.contains(families[product.family].attributes, attribute.code));
            },

            /**
             * Get the value in the given collection for the given lcoale and scope
             * @param Array  values
             * @param Object attribute
             * @param String locale
             * @param String scope
             *
             * @return {*}
             */
            getValue: function (values, attribute, locale, scope) {
                locale = attribute.localizable ? locale : null;
                scope  = attribute.scopable ? scope : null;

                return _.findWhere(values, {scope: scope, locale: locale});
            },

            /**
             * Generate a single value for the given attribute, scope and lcoale
             * @param Object attribute
             * @param String locale
             * @param String scope
             *
             * @return {*}
             */
            generateValue: function (attribute, locale, scope) {
                locale = attribute.localizable ? locale : null;
                scope  = attribute.scopable ? scope : null;

                return {
                    'locale': locale,
                    'scope':  scope,
                    'value':  attribute.empty_value
                };
            },

            /**
             * Generate all missing values for an attribute
             * @param values
             * @param attribute
             * @param locales
             * @param channels
             * @param currencies
             *
             * @return {*}
             */
            generateMissingValues: function (values, attribute, locales, channels, currencies) {
                _.each(locales, _.bind(function (locale) {
                    _.each(channels, _.bind(function (channel) {
                        var newValue = this.getValue(
                            values,
                            attribute,
                            locale.code,
                            channel.code
                        );

                        if (!newValue) {
                            newValue = this.generateValue(attribute, locale.code, channel.code);
                            values.push(newValue);
                        }

                        if ('pim_catalog_price_collection' === attribute.type) {
                            newValue.value = this.generateMissingPrices(newValue.value, currencies);
                        }

                    }, this));
                }, this));

                return values;
            },

            /**
             * Generate missing prices in the given collection for the given currencies
             * @param Array prices
             * @param Array currencies
             *
             * @return Array
             */
            generateMissingPrices: function (prices, currencies) {
                _.each(currencies, function (currency) {
                    var price = _.findWhere(prices, {currency: currency.code});

                    if (!price) {
                        price = {data: null, currency: currency.code};
                        prices.push(price);
                    }

                });

                return prices;
            }
        };
    }
);
