'use strict';

define(
    [
        'jquery',
        'underscore',
        'backbone',
        'routing',
        'pim/form-builder',
        'oro/messenger',
        'text!pim/template/attribute-option/validation-error'
    ],
    function (
        $,
        _,
        Backbone,
        Routing,
        FormBuilder,
        messenger,
        errorTemplate
    ) {
        var CreateOptionView = Backbone.View.extend({
            errorTemplate: _.template(errorTemplate),
            attribute: null,
            initialize: function (options) {
                this.attribute = options.attribute;
            },
            createOption: function () {
                var deferred = $.Deferred();

                FormBuilder.build('pim/attribute-option-form').done(_.bind(function (form) {
                    var modal = new Backbone.BootstrapModal({
                        modalOptions: {
                            backdrop: 'static',
                            keyboard: false
                        },
                        allowCancel: true,
                        okCloses: false,
                        title: _.__('pim_enrich.form.attribute_option.add_option_modal.title'),
                        content: '',
                        cancelText: _.__('pim_enrich.form.attribute_option.add_option_modal.cancel'),
                        okText: _.__('pim_enrich.form.attribute_option.add_option_modal.confirm')
                    });
                    modal.open();

                    form.setElement(modal.$('.modal-body')).render();

                    modal.on('cancel', deferred.reject);
                    modal.on('ok', _.bind(function () {
                        form.$('.validation-errors').remove();

                        $.ajax({
                            method: 'POST',
                            url: Routing.generate(
                                'pim_enrich_attributeoption_create',
                                { attributeId: this.attribute.id }
                            ),
                            data: JSON.stringify(form.getData())
                        }).done(function (option) {
                            modal.close();
                            messenger.notificationFlashMessage(
                                'success',
                                _.__('pim_enrich.form.attribute_option.flash.option_created')
                            );
                            deferred.resolve(option);
                        }).fail(_.bind(function (xhr) {
                            var response = xhr.responseJSON;

                            if (response.children &&
                                response.children.code &&
                                response.children.code.errors &&
                                response.children.code.errors.length
                            ) {
                                form.$('input[name="code"]').after(
                                    this.errorTemplate({
                                        errors: response.children.code.errors
                                    })
                                );
                            } else {
                                messenger.notificationFlashMessage(
                                    'error',
                                    _.__('pim_enrich.form.attribute_option.flash.error_creating_option')
                                );
                            }
                        }, this));
                    }, this));
                }, this));

                return deferred.promise();
            }
        });

        return function (attribute) {
            if (!attribute) {
                throw new Error('Attribute must be provided to create a new option');
            }

            var view = new CreateOptionView({ attribute: attribute });

            return view.createOption().always(function () {
                view.remove();
            });
        };
    }
);
