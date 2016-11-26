/**
 * Symfony Collection Handler
 * https://github.com/rogeriolino/symfony-collection.js
 */
(function($) {
    'use strict'

    var defaults = {
        collIndexName: 'collection-index',
        entryClassName: 'symfony-collection-entry'
    };

    /*
     * global function
     */

    window.SymfonyCollection = function (elem, props) {
        var elem = elem,
            target = null,
            config = $.extend({}, defaults, props),
            entryPrototype = (config.prototype || ''),
            targetSelector = (config.target || ''),
            btnAddSelector = (config.btnAdd || ''),
            btnRemoveSelector = (config.btnRemove || '');

        var init = function () {
            if (targetSelector.length) {
                target = elem.find(targetSelector);
            }

            if (!target) {
                target = elem;
            }

            elem.data(config.collIndexName, target.children().length);

            $.each(target.children(), function(i, child) {
                $(child).addClass(config.entryClassName);
            });

            if (btnAddSelector.length) {
                $(btnAddSelector).on('click', addEntry);
            }

            if (btnRemoveSelector.length) {
                // remove current entries
                target.find(btnRemoveSelector).on('click', function () {
                    var entry = $(this).parents('.' + config.entryClassName);
                    removeEntry(entry);
                });
            }
        };

        var invokeCallback = function (callbackName, args) {
            var callback = config[callbackName] || config[callbackName.toLowerCase()];
            
            if (typeof callback === 'function') {
                var evt = $.extend({
                    handler: elem,
                    target: target
                }, args);

                return callback(evt);
            }

            return null;
        };

        var removeEntry = function (entry) {
            var canRemove = invokeCallback('onPreRemove', { entry: entry });

            if (canRemove === false) {
                return;
            }
            
            entry.remove();
            
            invokeCallback('onRemove', { entry: entry });
        };

        var addEntry = function () {
            var index = elem.data(config.collIndexName),
                entry = $(entryPrototype.replace(/__name__/g, index));

            var canAdd = invokeCallback('onPreAdd', { entry: entry });

            if (canAdd === false) {
                return;
            }

            if (btnRemoveSelector) {
                // remove new entry
                entry.find(btnRemoveSelector).on('click', function () {
                    removeEntry(entry);
                });
            }

            target.append(entry.addClass(config.entryClassName));

            elem.data(config.collIndexName, index + 1);
            invokeCallback('onAdd', { entry: entry });
        };

        init();
    };

    /*
     * jQuery plugin
     */

    $.fn.collection = function (props) {
        props = props || {};

        $(this).each(function() {
            var elem = $(this);

            if (!props.target) {
                props.target = elem.data('target');
            }

            if (!props.btnAdd) {
                props.btnAdd = $(elem.data('btn-add'));
            }

            if (!props.btnRemove) {
                props.btnRemove = elem.data('btn-remove');
            }

            if (!props.prototype) {
                props.prototype = elem.data('prototype');
            }

            SymfonyCollection(elem, props);
        });

        return this;
    };

})(jQuery);