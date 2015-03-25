"use strict";

define(['routing', 'pim/config-manager', 'pim/attribute-manager'], function (Routing, ConfigManager, AttributeManager) {
    return {
        getAttributeGroupsForProduct: function(product)
        {
            var promise = $.Deferred();

            $.when(
                ConfigManager.getEntityList('attributegroups'),
                AttributeManager.getAttributesForProduct(product)
            ).done(_.bind(function(attributeGroups, productAttributes) {
                var activeAttributeGroups = {};
                _.each(attributeGroups, function(attributeGroup) {
                    if (_.intersection(attributeGroup.attributes, productAttributes).length > 0) {
                        activeAttributeGroups[attributeGroup.code] = attributeGroup;
                    }
                });

                promise.resolve(activeAttributeGroups);
            }, this));

            return promise.promise();
        },
        getAttributeGroupValues: function(values, attributeGroup)
        {
            var filteredValues = {};
                _.each(values, _.bind(function(productValue, attributeCode) {
                    if (attributeGroup && -1 !== attributeGroup.attributes.indexOf(attributeCode)) {
                        filteredValues[attributeCode] = productValue;
                    }
                }, this));

                return filteredValues;
        },
        getAttributeGroupForAttribute: function(attributeGroups, attribute) {
            var result = null;

            _.each(attributeGroups, function(attributeGroup) {
                if (-1 !== attributeGroup.attributes.indexOf(attribute)) {
                    result = attributeGroup.code;
                }
            });

            return result;
        }
    };
});