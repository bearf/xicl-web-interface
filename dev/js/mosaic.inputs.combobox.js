// --- mosaic.inputs.combobox ---
// version of oct 10, 2011

// later

// todo: использовать mosaic.widgets.suggest
// todo: переименовать label в value
// todo: метод с вызовом filter из вызова filter что-то уж совсем жесток

// todo: dropdown button / http://wiki.kirkazan.ru/pages/viewpage.action?pageId=42043895
// todo: opera: неправильная вертикальная позиция текста в background
// todo: IE8: неправильная вертикальная позиция текста в background
// todo: IE8: текст не выделяется при клике
// todo: IE7: неправильная вертикальная позиция текста в background
// todo: IE7: текст не выделяется при клике


(function($) {




    // --- PRE-PHASE ---

    if (!window.mosaic) { throw('mosaic.inputs.combobox.js: global object [mosaic] not found'); }
    if (!window.mosaic.widgets) { throw('mosaic.inputs.combobox.js: global object [mosaic.widgets] not found'); }
    if (!window.mosaic.widgets.dropdown) { throw('mosaic.inputs.combobox.js: global object [mosaic.widgets.dropdown] not found'); }
    if (!window.mosaic.inputs) { throw('mosaic.inputs.combobox.js: global object [mosaic.inputs] not found'); }

    if (window.mosaic.inputs.combobox) { return false; }

    // --- end of PRE-PHASE




    // --- PRIVATE GLOBAL FIELDS ---
    // any private field should start from "_" prefix
        //var

            // end of PRIVATE GLOBAL FIELDS ---
            //;




    // --- PSEUDO-CONSTRUCTOR ---

    // idNamespace points to:
    //      - id/name of the central DOM element of control
    //      - any DOM element inside control
    //      - any jQuery-object inside control
    window.mosaic.inputs.combobox = function(idCombobox) {
        var
                _context        = {}
            ,   _constructor    = function() {  }
            ,   _self
            ;

        // search of container for control
        // any jQuery-object fields should start from "$" prefix
        _context.$container     = _container(idCombobox); if (!_context.$container) { return {
                '$create'    : function(id, settings) {
                    var $combobox = (function($container) {
                        $container.attr('id', id + 'Container').addClass('mosaic-inputs-combobox-outer-container');

                        (function($dropdown) {
                            // todo: hintMosaicCombobox
                            // todo: dropdownWidthMosaicCombobox
                            return $dropdown;
                        })(mosaic.widgets.dropdown().$create(id + 'DropdownBox')).appendTo($container);

                        (function($comboboxContainer) {
                            $comboboxContainer.addClass('mosaic-inputs-combobox-container');

                            (function($hidden) {
                                return  $hidden.attr('id', id);
                            })($('<input type="hidden" />')).appendTo($comboboxContainer);

                            (function($value) {
                                return $value.addClass('mosaic-inputs-combobox-value');
                            })($('<div></div>')).appendTo($comboboxContainer);

                            (function($editArea) {
                                $editArea.addClass('mosaic-inputs-combobox-editable-area');

                                (function($background) {
                                    return $background.addClass('mosaic-inputs-combobox-editable-area-background');
                                })($('<div></div>')).appendTo($editArea);

                                (function($input) {
                                    return  $input
                                                .attr('name', id + 'Edit')
                                                .attr('autocomplete', 'off')
                                                .addClass('mosaic-inputs-combobox')
                                                ;
                                })($('<input type="text" />')).appendTo($editArea);

                                return $editArea;
                            })($('<div></div>')).appendTo($comboboxContainer);

                            (function($button) {
                                return $button.addClass('mosaic-inputs-combobox-button').html('search');
                            })($('<a href="javascript:void(0);"></a>')).appendTo($comboboxContainer);

                            return $comboboxContainer;
                        })($('<div></div>')).appendTo($container);

                        KIR.controls.parse($container[0]);

                        return $container;
                    })($('<div></div>'));

                    combobox($combobox)
                            .apply(settings)
                            .context().dropdown
                                .settings({ 'width':'100%' })
                                ;

                    return $combobox;
                }
        }; }

        _context.$value         = _context.$container.find('.mosaic-inputs-combobox-value');
        _context.$editable      = _context.$container.find('.mosaic-inputs-combobox-editable-area');
        _context.$background    = _context.$container.find('.mosaic-inputs-combobox-editable-area-background');
        _context.$hidden        = _context.$container.find('input[type=hidden]');
        _context.idCombobox     = _context.$hidden.attr('id');
        _context.$edit          = _context.$container.find('input[type=text]');
        _context.$btn           = _context.$container.find('.mosaic-inputs-combobox-button');
        _context.dropdown       = mosaic.widgets.dropdown($('[id$="' + _context.idCombobox + 'DropdownBox"]', _context.$container));

        // create object using fake function
        _constructor.prototype  = _proto;
        _self                   = new _constructor();

        // create function returning context inside created object
        _self.context = function() {
            return _context;
        };

        // finish pseudo-constructor
        return _self;
    };

    // --- end of PSEUDO-CONSTRUCTOR ---




    // --- SHORTCUT ---

    var
            combobox = window.mosaic.inputs.combobox

        // --- end of SHORTCUT ---
        ;




    // --- PUBLIC LOCAL METHODS  ---
    var _proto = {

            // must be invoked only at first time!
            // this method should run any initialization
            // required at the first time
            // like event handling
            apply: function(settings) {
                var
                        context = this.context()
                    ;

                // apply settings to the element
                _settings(this, settings);

                // default dropdown settings
                context.dropdown.settings({
                        'filter': function(selfDropdown, handler) {
                            var
                                    searchValue = this.context().$edit.val()
                                ,   tokens      = searchValue.split(/\s/g)
                                ;

                            inside.core.js.executeSafe(
                                    _settings(this).filter
                                ,   function(items) {
                                        $.each(items, function(index, item) {
                                            $.each(tokens, function(index, token) {
                                                var re = new RegExp(
                                                        ['(', token.replace(/\(/g, '\\(').replace(/\)/g, '\\)'), ')'].join('')
                                                    ,   'i'
                                                );
                                                item.label = item.label.replace(
                                                        re
                                                    ,   '<strong>$1</strong>'
                                                );
                                            });
                                        });
                                        handler(items);
                                    }
                            )(this, searchValue);
                        }.bind(this)

                    ,   'onselect': function(selfDropdown, label, data) {
                            return _select(
                                    this
                                ,   label.replace(/<[^>]*>/g, '')
                                ,   data
                            );
                        }.bind(this)

                        // called when ESC or out-click occurs on dropdown
                        // or dropdown.cancel() (which will never be invoked there)
                        // so it is blur
                    ,   'oncancel': function(selfDropdown, params) {
                            // if ESC pressed - close dropdown but keep input focused
                            _settings(this)[_SETTINGS_CANCEL_ON_ESC] || !params.ESC ? _blur(this) : void(0);
                        }.bind(this)

                    ,   'onclose': function(selfDropdown) {
                            // nothing here
                        }.bind(this)

                        // focus on item, not component itself
                    ,   'onfocus': function(selfDropdown, $liChosen) {
                            _tryFocusItem(this, $liChosen);
                        }.bind(this)

                    ,   'preventHideOnClick': function(selfDropdown, e) {
                            // нафига эта строка?!
                            // !this.context().$edit.val().length ? _clear(this): void(0);
                            return  $(e.target).closest('.mosaic-inputs-combobox-container').size() > 0
                                &&  $(e.target).closest('[id$="' + this.context().idCombobox + 'Container"]').size() > 0
                                    ;
                        }.bind(this)

                    ,   'getOffset': function(selfDropdown) {
                            return _getOffset(this);
                        }.bind(this)

                    ,   'width':    context.$container.outerWidth()
                                        ? context.$container.outerWidth() + 'px'
                                        : parseInt(context.$container.css('width')) + 'px'
                });

                context.$value.click(function(e) {
                    _focus(this);
                }.bind(this));
                context.$btn.keypress(function(e) {
                     inside.code.KEY_TAB == e.keyCode ? _blur(this) : void(0);
                }.bind(this));
                context.$btn.click(function() {
                    _focus(this); _callSearch(this);
                }.bind(this));
                $(function() {
                    KIR.events(context.$edit).typing(function(e, namespace, params) {
                        // prevent suggestion call on typing if needed
                        _settings(this)[_SETTINGS_ENABLE_TYPING] ? _callSearch(this) : void(0);
                    }.bind(this)).change(function(e, namespace, params) {
                        _checkBackgroundMatchWithInput(this);
                    }.bind(this));
                }.bind(this));
                context.$edit.keydown(function(e) {
                    _handleSearchByKeys(this, e); // enter and alt+down
                }.bind(this));

                // потеря фокуса по click, когда dropdown не выпал
                $(document).click(function(e) {
                    // if closed - ignore
                    if (!_shown(this)) { return; }
                    // if click inside component - ignore
                    if ($(e.target).is(context.$container) || context.$container.has(e.target).size()) { return; }
                    // if dropdown is shown - this click will be handled by it
                    if (context.dropdown.shown()) { return; }

                    _blur(this);
                }.bind(this));

                return this;
            } // end of apply

            // shortcut to private "_settings"
        ,   settings: function(settings) {
                return _settings(this, settings);
            } // end of settings

            // shortcut for private "_clear" method
        ,   clear: function() {
                _clear(this); return this;
            } // end of clear

            // cancel = blur
        ,   cancel: function() {
                _cancel(this); return this;
            }

        ,   shown: function() {
                return _shown(this);
            }

        ,   hidden: function() {
                return _hidden(this);
            }

        ,   focus: function() {
                _focus(this); return this;
            }

        ,   blur: function() {
                _blur(this); return this;
            }

    }; // --- end of PUBLIC LOCAL METHODS  ---




    // --- PRIVATE CONSTS ---

    var
            // class for value under edit
            _EDITABLE_CLASSNAME         = 'mosaic-inputs-combobox-editable-area-active'

            // class for value when no text inside
        ,   _VALUE_PROMPT_CLASSNAME       = 'mosaic-inputs-combobox-value-prompt'

        ;   // --- end of PRIVATE CONSTS ---




    // --- PRIVATE GLOBAL METHODS ---

    var
            // find container for given namespace
            // finds object with id/name=idNamespace and then
            // its closest parent with selector = ".container-for-idNamespace-id-object"
            _container = function(idCombobox) {
                return inside.core.util.object(idCombobox, '.mosaic-inputs-combobox-outer-container')
            } // end of container

            // returns id for dropdown box attached to input
        ,   _dropdownBoxId = function(idCombobox) {
                return _container(idCombobox).find('input[type=hidden]').attr('name') + 'DropdownBox';
            }

        ; // --- end of PRIVATE GLOBAL METHODS ---




    // --- PRIVATE LOCAL METHODS ---

    var
            // Call suggestion and select text in input.
            _callSearch = function(self) {
                self.context().dropdown.call();
            } // end of _callSearch

        ,   _selectText = function(self) {
                var
                        context = self.context()
                    ,   input = context.$edit.get(0)
                    ;

                // select all the value by text range
                if (input.createTextRange) {
                    var range = input.createTextRange();
                    range.collapse(false);
                    range.select();
                } else if (input.setSelectionRange) {
                    input.setSelectionRange(0, context.$edit.val().length);
                    input.focus();
                }
            }

        ,   _show = function(self) {
                (function(context) {
                    context.$editable.addClass(_EDITABLE_CLASSNAME);

                    _togglePrompt(self);

                    context.$edit.focus().val(context.$value.html());
                    context.$background.html(context.$value.html());
                })(self.context());
            }

        ,   _focus = function(self) {
                if (!_hidden(self)) { return self; }

                _show(self);
                _selectText(self);
                inside.core.js.executeSafe(_settings(self)[_SETTINGS_ON_FOCUS])(self);
            }

        ,   _blur = function(self) {
                !self.context().$edit.val().length
                        ?   (function() {
                                _clear(self); _loseFocus(self);
                            })()
                        :   (function() {
                                _settings(self)[_SETTINGS_CANCEL_ON_EXIT]
                                    ?   _cancel(self)
                                    :   _select(self,
                                                self.context().$edit.val(), { 'value' : '' });
                            })();
            }

        ,   _loseFocus = function(self) {
                self.context().$editable.removeClass(_EDITABLE_CLASSNAME);

                _togglePrompt(self);

                inside.core.js.executeSafe(_settings(self)[_SETTINGS_ON_BLUR])(self);
            }

        ,   _shown = function(self) {
                return self.context().$editable.hasClass(_EDITABLE_CLASSNAME);
            }

        ,   _hidden = function(self) {
                return !self.context().$editable.hasClass(_EDITABLE_CLASSNAME);
            }

            // <strong> must be removed from label before this method call
        ,   _select = function(self, label, data) {
                var realSubstitutedLabel = 'undefined' != typeof data.short
                        ? data.short
                        : $(['<span>', label, '</span>'].join('')).text()
                        ;

                self.context().$value.html(realSubstitutedLabel);
                self.context().$hidden.val(data.value);

                // otherwise:
                // 1. old_value = something typed into field
                // 2. then focus on input again and open suggestion
                // 3. real value = label
                // 4. select item
                // 5. typing fires because real value <> old_value
                KIR.events(self.context().$edit).namespace.evt.oldValue(realSubstitutedLabel);
                self.context().$edit.val(realSubstitutedLabel);

                var result = inside.core.js.executeSafe(_settings(self).onselect)(
                        self
                    ,   label
                    ,   data
                );
                result = 'undefined' == typeof result || result;

                if (result) { _loseFocus(self); }

                return result;
            }

        ,   _clear = function(self) {
                self.context().$value.html('');
                self.context().$edit.val('');
                self.context().$hidden.val('');
                KIR.events(self.context().$edit).namespace.evt.oldValue('');
                inside.core.js.executeSafe(_settings(self).onclear)(self);
            } // end of _clear

        ,   _cancel = function(self) {
                KIR.events(self.context().$edit).namespace.evt.oldValue(
                        self.context().$value.text()
                );
                self.context().$edit.val(
                        self.context().$value.text()
                );
                inside.core.js.executeSafe(_settings(self).oncancel)(self);

                if (self.context().dropdown.shown()) { self.context().dropdown.close(); }

                _loseFocus(self);
            } // end of _cancel

        ,   _checkBackgroundMatchWithInput = function(self) {
                if (!self.context().$edit.val() && !self.context().dropdown.shown()) {
                    self.context().$background.html('');
                } else if (self.context().$edit.val() && 0 != self.context().$background.html().indexOf(self.context().$edit.val())) {
                    self.context().$background.html('');
                }
            }

        ,   _getOffset = function(self) {
                var offset = self.context().$container.offset();
                offset.top += self.context().$container.outerHeight();

                return offset;
            }

        ,   _tryFocusItem = function(self, $liChosen) {
                if (!$liChosen.length) { return false; }

                if (_settings(self)[_SETTINGS_ENABLE_BACKGROUND_SUBSTITUTION]) {
                    // place hint behind typed value...
                    // todo: more accurate, not just .text(). Possibly, use .data()
                    self.context().$background.html($liChosen.text());
                    // ... and clear it if no match found
                    _checkBackgroundMatchWithInput(self);
                }
            }

        ,   _togglePrompt = function(self) {
                // if value is the same as the prompt
                // then clear value and remove prompt class
                if (_settings(self)[_SETTINGS_PROMPT] == self.context().$value.html()) {
                    self.context().$value
                            .html('')
                            .removeClass(_VALUE_PROMPT_CLASSNAME);
                } else if (_settings(self)[_SETTINGS_PROMPT] && !self.context().$value.html().length) {
                    // if we have the prompt and no value applied
                    // then show prompt
                    self.context().$value
                            .html(_settings(self)[_SETTINGS_PROMPT])
                            .addClass(_VALUE_PROMPT_CLASSNAME);
                }
            }

        // --- end of PRIVATE LOCAL METHODS ---
        ;




    // --- LOCAL EVENT HANDLERS ---

    var

            // handles search by enter and alt + down
            _handleSearchByKeys = function(self, e) {
                var search = function() {
                    _callSearch(self);
                    e.preventDefault(); return false;
                };

                if (inside.code.KEY_DOWN == e.which && e.altKey ) { return search(); }
                if (self.context().dropdown.focused()) { return undefined; }
                //if (inside.code.KEY_ENTER == e.which) { return search(); }
            } // end of _handleSearchByEnter

        ;   // --- end of LOCAL EVENT HANDLERS ---




    // --- STATE KEYS ---

    var
            // key for storing settings
            _STATE_KEY_SETTINGS             = 'mosaic-inputs-combobox-settings'

        ;   // --- end of STATE KEYS ---




    // --- STATE FUNCTIONS ---

    var
            // method for retrieving or setting inner property of namespace
            // binded to concrete instance on the page
            _state = function(self, name, value, defaults) {
                if ('undefined' == typeof value) {
                    return  'undefined' == typeof self.context().$container.data(name)
                                ? defaults
                                : self.context().$container.data(name);
                } else {
                    self.context().$container.data(name, value);
                    return _state(self, name);
                }
            } // end of _state

            // method for setting or retrieving settings of namespace.
        ,   _stateSettings = function(self, settingsValue) {
                return _state(self, _STATE_KEY_SETTINGS, settingsValue, _defaultSettings());
            } // end of _state_settings

        ;   // --- end of STATE FUNCTIONS ---




    // --- SETTINGS KEYS ---

    var
            // prompt
            _SETTINGS_PROMPT                            = 'prompt'

        ,   _SETTINGS_ENABLE_TYPING                     = 'typing'

        ,   _SETTINGS_ENABLE_BACKGROUND_SUBSTITUTION    = 'subst'

        ,   _SETTINGS_CANCEL_ON_EXIT                    = 'cancelOnExit'

        ,   _SETTINGS_CANCEL_ON_ESC                     = 'cancelOnESC'

        ,   _SETTINGS_ON_FOCUS                          = 'onFocus'

        ,   _SETTINGS_ON_BLUR                           = 'onBlur'

        ;   // --- end of SETTINGS KEYS ---





    // --- DEFAULT SETTINGS ---

    var

            _defaultSettings = function() {
                var defaults = {};

                defaults[_SETTINGS_PROMPT]                            = '';
                defaults[_SETTINGS_ENABLE_TYPING]                   = true;
                defaults[_SETTINGS_ENABLE_BACKGROUND_SUBSTITUTION]  = true;
                defaults[_SETTINGS_CANCEL_ON_EXIT]                  = true;
                defaults[_SETTINGS_CANCEL_ON_ESC]                   = true;
                defaults[_SETTINGS_ON_FOCUS]                        = function() { return true; };
                defaults[_SETTINGS_ON_BLUR]                         = function() { return true; };

                return defaults;
            } // end of _defaultSettings

        ; // --- end of DEFAULT SETTINGS ---




    // --- SETTINGS FUNCTIONS ---

    var
            _settings = function(self, settings) {
                var
                        _settings = _stateSettings(self)
                    ;

                if (settings) {
                    for (var key in settings) {
                        if (_SETTINGS_PROMPT == key) {
                            _prompt(self, settings[key]);
                        }

                        _settings[key] = settings[key];
                    }

                    _stateSettings(self, _settings);
                }

                return _settings;
            } // end of _settings

        ,   _prompt = function(self, value) {
                var
                        oldPrompt = _settings(self)[_SETTINGS_PROMPT]
                    ;

                // if prompt is not present in input field, make no changes
                if (oldPrompt != self.context().$value.html()) { return undefined; }

                // if new prompt is empty, clear old prompt
                if ('undefined' == typeof value || '' == value) {
                    self.context().$value
                            .removeClass(_VALUE_PROMPT_CLASSNAME);
                }

                // if new prompt is not empty, put classname
                if ('string' == typeof value && '' != value) {
                    self.context().$value
                            .addClass(_VALUE_PROMPT_CLASSNAME);
                }

                // put new prompt
                self.context().$value.html(value)
            } // end of _prompt

        ;   // --- end of SETTINGS FUNCTIONS ---





    // --- GLOBAL EVENT HANDLERS ---

    $(document).click(function(e) {
        // place your code for event handling like this
    });

    // --- end of GLOBAL EVENT HANDLERS ---

})(jQuery);