/*
 * Daniel Coull <d.coull@suttonsilver.co.uk>
 * 2019-2020
 *
 */

define([
    'Magento_Ui/js/form/element/ui-select',
    'jquery',
    'underscore'
], function (Select, $, _) {
    'use strict';

    return Select.extend({
        defaults: {
            validationUrl: false,
            loadedOption: [],
            validationLoading: true
        },

        /** @inheritdoc */
        initialize: function () {
            this._super();

            this.validateInitialValue();

            let self = this;
            $('.action-menu-item _with-checkbox').on('click', function () {
                self.validateInitialValue();
            });

            return this;
        },

        /**
         * Validate initial value actually exists
         */
        validateInitialValue: function () {
            let value = JSON.stringify(this.value());
            if (value !== '') {
                $.ajax({
                    url: this.validationUrl,
                    type: 'GET',
                    dataType: 'json',
                    context: this,
                    data: {
                        ids:value
                    },

                    /** @param {Object} response */
                    success: function (response) {
                        if (!_.isEmpty(response)) {
                            var existingOptions = this.options();

                            let self = this;
                            _.each(response.options, function (opt) {
                                existingOptions.push(opt);
                                self.total += 1;
                            });

                            this.options(existingOptions);
                        }
                    },

                    /** set empty array if error occurs */
                    error: function () {
                        this.options([]);
                        this.setCaption();
                    },

                    /** stop loader */
                    complete: function () {
                        this.validationLoading(false);
                        this.setCaption();
                    }
                });
            } else {
                this.validationLoading(false);
            }
        }
    });
});
