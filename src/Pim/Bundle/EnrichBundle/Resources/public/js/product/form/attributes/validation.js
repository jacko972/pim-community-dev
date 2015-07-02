'use strict';
/**
 * Validation extension
 *
 * @author    Julien Sanchez <julien@akeneo.com>
 * @author    Filips Alpe <filips@akeneo.com>
 * @copyright 2015 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
define(
    [
        'jquery',
        'underscore',
        'backbone',
        'pim/form',
        'oro/mediator',
        'oro/messenger',
        'pim/field-manager',
        'pim/product-edit-form/attributes/validation-error',
        'pim/user-context'
    ],
    function ($, _, Backbone, BaseForm, mediator, messenger, FieldManager, ValidationError, UserContext) {
        return BaseForm.extend({
            initialize: function () {
                mediator.off(null, null, 'context:product:form:validation');
                mediator.on('validation_error', _.bind(this.validationError, this), 'form:product:validation');

                BaseForm.prototype.initialize.apply(this, arguments);
            },
            validationError: function (validationErrors) {
                this.removeValidationErrors();
                this.addValidationErrors(validationErrors).then(function () {
                    mediator.trigger('product:action:post_validation_error');
                });
            },
            removeValidationErrors: function () {
                var fields = FieldManager.getFields();

                _.each(fields, function (field) {
                    field.setValid(true);
                    field.removeElement('footer', 'validation');
                });
            },
            addValidationErrors: function (data) {
                // Global errors with an empty property path
                if (data[''] && data[''].message) {
                    messenger.notificationFlashMessage('error', data[''].message);
                }

                return $.when.apply($, _.map(data.values, _.bind(function (fieldErrors, attributeCode) {
                    return FieldManager.getField(attributeCode)
                        .done(_.bind(function (field) {
                            field.addElement(
                                'footer',
                                'validation',
                                new ValidationError(fieldErrors, this)
                            );

                            field.setValid(false);
                        }, this)
                    );
                }, this)));
            },
            changeContext: function (locale, scope) {
                if (locale) {
                    UserContext.set('catalogLocale', locale);
                }

                if (scope) {
                    UserContext.set('catalogScope', scope);
                }
            }
        });
    }
);
