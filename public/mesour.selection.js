/**
 * This file is part of the Mesour Selection (http://grid.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */
var mesour = !mesour ? {} : mesour;
mesour.selection = !mesour.selection ? {} : mesour.selection;

(function ($) {
    var _toggle = function (el, options) {
        if (el.hasClass('btn-default')) {
            _active(el, options);
        } else {
            _inactive(el, options);
        }
    };
    var _active = function (el, options, icon) {
        icon = !icon ? mesour.selection.ICON_CHECKED : icon;
        el.removeClass(options.inactiveClass).addClass(options.activeClass);
        el.html('<span class="' + options['iconPrefix'] + mesour.selection.getIcon(icon) + '"></span>');
    };
    var _inactive = function (el, options) {
        el.addClass(options.inactiveClass).removeClass(options.activeClass);
        el.html('&nbsp;&nbsp;&nbsp;&nbsp;');
    };
    var CheckboxHelper = function (element, options, parentOptions) {
        var name = element.attr(parentOptions.nameAttr),
            mainCheckbox = $(parentOptions.mainSelector).filter('[' + parentOptions.nameAttr + '="' + name + '"]');

        element.find('[data-status]').on('click', function (e) {
            e.preventDefault();
            var $this = $(this),
                status = $this.attr('data-status');

            options['iconPrefix'] = mainCheckbox.attr('data-icon-prefix');

            if (status === 'm_-inverse') {
                $(parentOptions.itemsSelector).filter('[' + parentOptions.nameAttr + '="' + name + '"]').each(function () {
                    var $_this = $(this);
                    _toggle($_this, options);
                });
            } else {
                $(parentOptions.itemsSelector).filter('[' + parentOptions.nameAttr + '="' + name + '"]').each(function () {
                    var $_this = $(this),
                        statuses = $_this.attr('data-status').split('|'),
                        isValid = false;
                    for (var i = 0; i < statuses.length; i++) {
                        if (status == statuses[i]) {
                            _active($_this, options);
                            isValid = true;
                        }
                    }
                    if (!isValid) {
                        _inactive($_this, options);
                    }
                });
            }
            mainCheckbox.trigger('selection-change.selection');
            element.trigger('selection-change.selection');
        });
    };
    var MainChecker = function (element, options, parentOptions) {
        var name = element.attr(parentOptions.nameAttr);
        options['iconPrefix'] = element.attr('data-icon-prefix');
        var toggle = function (fromMain) {
            var one = false,
                all = true,
                no = 0,
                count = 0,
                values = mesour.selection.getValues(name);

            $.each(values, function (id, isChecked) {
                if (isChecked) {
                    one = true;
                } else {
                    no++;
                    all = false;
                }
                count++;
            });
            var nothing = count === no;
            if (fromMain) {
                if (element.hasClass('btn-default') || element.hasClass("btn-indeterminate")) {
                    $(parentOptions.itemsSelector).filter('[' + parentOptions.nameAttr + '="' + name + '"]').each(function () {
                        var $_this = $(this);
                        _active($_this, options);
                    });
                    all = true;
                } else {
                    $(parentOptions.itemsSelector).filter('[' + parentOptions.nameAttr + '="' + name + '"]').each(function () {
                        var $_this = $(this);
                        _inactive($_this, options);
                    });
                    all = false;
                    one = false;
                }
            }
            element.removeClass('btn-indeterminate');
            if (all) {
                _active(element, options);
            } else if (one && !nothing) {
                _active(element, options, mesour.selection.ICON_MINUS);
                element.addClass('btn-indeterminate');
            } else {
                _inactive(element, options);
            }
            element.trigger('change.selection');
        };
        element.on({
            'click.selection': function (e) {
                e.preventDefault();
                e.stopPropagation();
                toggle(true);
            },
            'selection-change.selection': function () {
                toggle();
            }
        });
    };
    var Checker = function (element, options, parentOptions) {
        var name = element.attr(parentOptions.nameAttr),
            mainCheckbox = $(parentOptions.mainSelector).filter('[' + parentOptions.nameAttr + '="' + name + '"]');

        options['iconPrefix'] = mainCheckbox.attr('data-icon-prefix');

        var toggle = function () {
            _toggle(element, options);
            mainCheckbox.trigger('selection-change.selection');
            element.trigger('change.selection');
        };
        element.on({
            'click.selection': function () {
                toggle();
            },
            'selection-change.selection': function () {
                toggle();
            }
        });
    };
    var Selection = function (options) {

        this.ICON_MINUS = 'minus';
        this.ICON_CHECKED = 'check';

        var icons = {};
        icons[this.ICON_MINUS] = 'minus';
        icons[this.ICON_CHECKED] = 'check';

        this.getValues = function (name) {
            var values = {};
            $(options.itemsSelector).filter('[' + options.nameAttr + '="' + name + '"]').each(function () {
                var _item = $(this);
                values[_item.attr(options.idAttr)] = _item.hasClass(options.item.activeClass);
            });
            return values;
        };

        this.getItems = function (name) {
            return $(options.itemsSelector).filter('[' + options.nameAttr + '="' + name + '"]');
        };

        this.getMainCheckbox = function (name) {
            return $(options.mainSelector).filter('[' + options.nameAttr + '="' + name + '"]');
        };

        this.setIcon = function (iconName, icon) {
            icons[iconName] = icon;
        };

        this.getIcon = function (iconName) {
            return icons[iconName];
        };

        this.create = function () {
            $(options.itemsSelector).each(function () {
                var $this = $(this),
                    instance = $this.data('_m-selection');
                if (!instance) {
                    instance = new Checker($this, options.item, options);
                }
            });
            $(options.mainSelector).each(function () {
                var $this = $(this),
                    instance = $this.data('_m-selection');
                if (!instance) {
                    instance = new MainChecker($this, options.item, options);
                }
            });
            $(options.dropDownSelector).each(function () {
                var $this = $(this),
                    instance = $this.data('_m-selection');
                if (!instance) {
                    instance = new CheckboxHelper($this, options.item, options);
                }
            });
        };
    };
    mesour.core.createWidget('selection', new Selection({
        'itemsSelector': '.mesour-select-checkbox',
        'mainSelector': '.mesour-main-checkbox',
        'dropDownSelector': '.selection-dropdown',
        'nameAttr': 'data-name',
        'idAttr': 'data-id',
        'statusAttr': 'data-status',
        'item': {
            'activeClass': 'btn-warning',
            'inactiveClass': 'btn-default'
        }
    }));

    mesour.on.live('grid-selection', mesour.selection.create);
})(jQuery);