// --- mosaic.widgets.dropdown ---
// version of nov 28, 2011

// later

// todo: IE7: _now есть null или не является объектом
// todo: scrollbars
// todo: cache
// todo: тени под списком
// todo: не терять фокус при обновлении списка
// todo: $extend для настроек

(function($) {

    // --- PRE-PHASE ---

    // Check for existence all necessary namespaces.
    // Check for existence of namespace you're defining.

    if (!window.mosaic) { throw('global object [mosaic] not found'); }
    if (!window.mosaic.widgets) { throw('global object [mosaic.widgets] not found'); }

    if  (window.mosaic.widgets.dropdown) { return false; }

    // --- end of PRE-PHASE




    // --- PRIVATE GLOBAL FIELDS ---

    // These fields act on all widget instances on the page.
    // Any private field should start from "_" prefix.

        //var

            // end of PRIVATE GLOBAL FIELDS ---
            //;




    // --- PSEUDO-CONSTRUCTOR ---

    // This function creates context containing instance-specific
    // variables like idNamespace and any jQuery-elements inside widget.
    // Also it creates public object representing widget itself on the base
    // of "_proto" object. Then it create "context()" method returning
    // for this widget. Finally it returns created widget object itself.

    // idDropdown is point to HTML-instance of the widget on the page.
    // It should point to:
    //      - id/name of the central DOM element of widget (input field in case of inputs);
    //      - any DOM element inside widget;
    //      - any jQuery-object inside widget.

    window.mosaic.widgets.dropdown = function(idDropdown) {
        var
                _context        = {}
            ,   _constructor    = function() {  }
            ,   _self
            ;

        // saving id
        _context.idDropdown      = idDropdown;

        // search of container for widget
        // any jQuery-object fields should start from "$" prefix
        _context.$container     = _container(idDropdown); if (!_context.$container) { return {
                '$create'    : function(id, settings) {
                    var $dropdown = (function($container) {
                        $container.addClass('mosaic-widgets-dropdown-container').attr('id', id);

                        (function($dropdown) {
                            $dropdown.addClass('mosaic-widgets-dropdown-dropdown');

                            (function($loading) {
                                return $loading.addClass('mosaic-widgets-dropdown-loading');
                            })($('<div>&nbsp;</div>')).appendTo($dropdown);
                            return $dropdown;

                        })($('<div></div>')).appendTo($container);

                        (function($emptyResults) {
                            return $emptyResults.addClass('mosaic-widgets-dropdown-empty-results');
                        })($('<div></div>')).appendTo($container);

                        (function($hint) {
                            return $hint.addClass('mosaic-widgets-dropdown-hint').addClass('mosaic-widgets-dropdown-hint-invisible');
                        })($('<div></div>')).appendTo($container);

                        return $container;
                    })($('<div></div>'));

                    dropdown($dropdown).apply(settings);

                    return $dropdown;
                }
        }; }

        // init all fields here
        _context.$dropdown      = _context.$container.find('.' + _DROPDOWN_DROPDOWN_CLASSNAME);
        _context.$hint          = _context.$container.find(_getHintSelector());
        _context.$empty         = _context.$container.find(_getEmptyResultsSelector());

        // create object using fake function
        _constructor.prototype  = _proto;
        _self                   = new _constructor();

        // create function returning context inside created object
        _self.context = function() {
            return _context;
        };

        return _self;
    };

    // --- end of PSEUDO-CONSTRUCTOR ---




    // --- SHORTCUT ---

    // Used to shortly point to widget inside private and public methods
    // without namespace prefixes.

    var
            dropdown = window.mosaic.widgets.dropdown

        // --- end of SHORTCUT ---
        ;




    // --- PUBLIC LOCAL METHODS ---

    // This is prototype of any instance of widget-object
    // with public methods.

    var _proto = {

            // Must be invoked only at first time!
            // This method applies settings, makes some initialization
            // and then sets up event handlers binded to this particular
            // widget by ".bind(this)". All event handlers are functions
            // from LOCAL EVENTS HANDLERS section.
            apply: function(settings) {
                _settings(this, settings);

                // any key-specific event handling
                $(document).keydown(function(e) {
                    // widget should be shown to proceed
                    if (_hidden(this)) { return undefined; }

                    // handling focusing items by arrow keys and
                    // selecting those by enter key
                    _handleFocusAndSelectByKeys(this, e);

                    _handleCancelByESC(this, e);

                    // prevent default browser reaction to pressing keys
                    return _preventDefaults(this, e);
                }.bind(this));

                // focus on item in dropdown box by mousemove
                this.context().$dropdown.mousemove(function(e) { _tryFocusByMouseMove(this, e); }.bind(this));

                // handle selecting item by mouse click
                this.context().$dropdown.click(function(e) { _trySelectByClick(this, e); }.bind(this));

                // handle exiting from widget through clicking out of it
                $(document).click(function(e) { _checkHideByClick(this, e); }.bind(this));

                return this;
            } // end of apply

            // shortcut to private "_settings"
        ,   settings: function(settings) {
                return _settings(this, settings);
            } // end of settings

            // show dropdown box if necessary
            // then filter items
        ,   call: function() {
                _call(this);
            } // end of call

            // cancel, abnormal termination: cancel all processing and close dropdown
        ,   cancel: function() {
                _cancel(this, {});
            } // end of cancel

            // close, normal termination: just close dropdown
        ,   close: function() {
                _close(this);
            } // end of close

            // shortcut to private "_moveTo()" method
        ,   moveTo: function(offset) {
                _moveTo(this, offset);
            } // end of move_to

            // shortcut to private "_shown()" method
        ,   shown: function() {
                return _shown(this);
            } // end of shown

            // shortcut to private "_showing()" method
        ,   showing: function() {
                return _showing(this);
            } // end of showing

            // shortcut to private "_hidden()" method
        ,   hidden: function() {
                return _hidden(this);
            } // end of hidden

            // shortcut to private "_isFocused()" method
        ,   focused: function() {
                return _isFocused(this);
            } // end of focused

    };

    // --- end of PUBLIC LOCAL METHODS ---




    // --- PUBLIC GLOBAL METHODS ---

    // These methods are attached to pseudo-constructor like
    // it fields. In result, you can invoke those as "namespace.method()".

    // --- end of PUBLIC GLOBAL METHODS ---




    // --- PRIVATE CONSTS ---

    var
            // any string const used as key to inner data values
            // should start from "namespace-name"-key.
            _KEY_STATE_VISIBILITY               = 'dropdown-key-state-visibility'

        ,   _VALUE_STATE_VISIBILITY_HIDDEN      = 101

        ,   _VALUE_STATE_VISIBILITY_HIDING      = 102

        ,   _VALUE_STATE_VISIBILITY_SHOWING     = 103

        ,   _VALUE_STATE_VISIBILITY_SHOWN       = 104

        ,   _KEY_FOCUSED_ITEM                   = 'dropdown-key-field-$li-focused'

            // storing data in list items
        ,   _KEY_ITEM_DATA                      = 'dropdown-key-item-data'

            // empty jQuery object for internal purposes
        ,   _EMPTY_JQUERY                       = $('somefakecountthatwontbefoundonthepage')

        ,   _NO_FOCUSED_ITEM                    = _EMPTY_JQUERY

            // bottom padding for container to embrace dropdown's shadow
        ,   _PADDING_FOR_SHADOW                 = 5

        ,   _DROPDOWN_CONTAINER_CLASSNAME       = 'mosaic-widgets-dropdown-container'

            // classname for dropdown
        ,   _DROPDOWN_DROPDOWN_CLASSNAME        = 'mosaic-widgets-dropdown-dropdown'

            // classname for items list
        ,   _DROPDOWN_LIST_CLASSNAME            = 'mosaic-widgets-dropdown-dropdown-items'

            // classname for list item
        ,   _DROPDOWN_ITEM_CLASSNAME            = 'mosaic-widgets-dropdown-dropdown-item'

        ,   _CLASSNAME_DROPDOWN_ITEM_DISABLED   = 'mosaic-widgets-dropdown-dropdown-item-disabled'

            // classname for focused item
        ,   _DROPDOWN_FOCUSED_ITEM_CLASSNAME    = 'mosaic-widgets-dropdown-dropdown-item-chosen'

        // --- end of PRIVATE CONSTS ---
        ;




    // --- PRIVATE GLOBAL METHODS ---

    // These methods are invoked in global context of all widgets
    // with no touch to concrete instances.

    var
            // Find container for given namespace.
            // Finds object with id/name=idNamespace and then
            // its closest parent with selector = ".container-for-idNamespace-id-object".
            _container = function(idDropdown) {
                return inside.core.util.object(idDropdown, '.' + _DROPDOWN_CONTAINER_CLASSNAME);
            } // end _container

            // create HTML structure representing dropdown list.
        ,   _createListHTML = function(items) {
                var
                        html = ''
                    ;

                $.each(items, function(index, item) { html += _createItemHTML(item); });
                html    =   '<ul class="' + _DROPDOWN_LIST_CLASSNAME + '">'
                        +       html
                        +   '</ul>';

                return html;
            } // _createListHTML

            // create HTML structure representing dropdown item.
        ,   _createItemHTML = function(item) {
                return  '<li class="' + _DROPDOWN_ITEM_CLASSNAME + ' ' + (item.data.disabled ? _CLASSNAME_DROPDOWN_ITEM_DISABLED : '') + '">'
                    +       '<a href="javascript:void(0);">'
                    +           item.label
                    +       '</a>'
                    +   '</li>';
            } // _createItemHTML

            // get data attached to list item
        ,   _dataOf = function($liItem) {
                return $liItem.data(_KEY_ITEM_DATA);
            } // end of _dataOf

            // get label attached to list item
        ,   _labelOf = function($liItem) {
                return $liItem.find('a').html();
            } // end of _labelOf

        ,   _hintOf = function($liItem) {
                return  'undefined' != typeof $liItem.data(_KEY_ITEM_DATA).hint
                                ? $liItem.data(_KEY_ITEM_DATA).hint
                                : _labelOf($liItem)
                                ;
            } // end of _hintOf

        ,   _forceHintOf = function($liItem) {
                return  'undefined' != typeof $liItem.data(_KEY_ITEM_DATA).forceHint
                                ? $liItem.data(_KEY_ITEM_DATA).forceHint
                                : false
                                ;
            } // end of _forceHintOf

        // --- end of PRIVATE GLOBAL METHODS ---
        ;




    // --- PRIVATE LOCAL METHODS ---

    // Each of these methods exists as one istance,
    // but it gets "self" object as first parameter
    // which represents conrete instance of widget.
    // Since, each of these methods executes in local
    // context of widget

    var
            _call = function(self) {
                // first filter items then show dropdown
                inside.core.js.executeSafe(
                        _settings(self).filter
                    ,   (function(focuser) {
                            return function(items) {
                                _processItems(self, items);
                                if (_hidden(self)) {
                                    _show(self, function() {
                                        focuser(self);
                                    });
                                } else {
                                    focuser(self);
                                } // check for hidden
                            }; // function(items)
                        })(function(self) {
                            // focus on the first item inside list or clear selection if no items found
                            if (_$items(self).length) {
                                var $focused = _$items(self).filter(function() { return !_dataOf($(this)).disabled; }).eq(0);
                                _focus(self, $focused);
                            } else {
                                _focus(self, _NO_FOCUSED_ITEM);
                            }
                        }) // focuser
                )(self);
            } // end of _process

        ,   _cancel = function(self, params) {
                inside.core.js.executeSafe(_settings(self).oncancel)(self, params);

                _close(self);
            } // end of _cancel

        ,   _close = function(self) {
                inside.core.js.executeSafe(_settings(self).onclose)(self);

                _hide(self);
            }

        ,   _select = function(self, $liItem) {
                var result = inside.core.js.executeSafe(_settings(self).onselect)(
                        self
                    ,   _labelOf($liItem)
                    ,   _dataOf($liItem)
                );

                if ('undefined' == typeof result || result) { _close(self); }
            } // end of _select

            // Handle data with items, render them and process other stuff.
            // "items" must be array of JSON-objects representing items.
            // If "items" is undefined then no rendering occures.
            // This method passed as param to "settings.filter()" inside "_call()" method
        ,   _processItems = function(self, items) {
                if ('undefined' != typeof items) {
                    self.context().$dropdown.html(_createListHTML(items));

                    // go through items and attach data
                    var $items = _$items(self);
                    $.each(items, function(index, item) {
                        $items.eq(index).data(_KEY_ITEM_DATA, item.data);
                        if (item.data.HTMLHandler) {
                            item.data.HTMLHandler(
                                    $items.eq(index)
                                ,   item.data
                            );
                        } // call HTMLHandler on item
                    });
                }

                _$items(self).length
                        ? self.context().$empty.removeClass(_CLASSNAME_EMPTY_RESULTS_VISIBLE)
                        : self.context().$empty.addClass(_CLASSNAME_EMPTY_RESULTS_VISIBLE)
                        ;
            } // end of _processItems

        ,   _slideIn = function(self, callback) {
                _stateVisibility(self, _VALUE_STATE_VISIBILITY_SHOWING);

                self.context().$container.css({
                        'display'   : 'block'
                    ,   'opacity'   : 1
                }).css({
                        'height'    :   self.context().$dropdown.outerHeight()
                                    +   _PADDING_FOR_SHADOW
                                    +   'px'
                });

                self.context().$dropdown.css({
                        'marginTop' : -self.context().$dropdown.outerHeight() - 1 + 'px'
                });

                self.context().$dropdown.animate(
                        { 'marginTop': 0 }
                    ,   mosaic.settings().animationDelay
                    ,   'swing'
                    ,   function() {
                            _stateVisibility(self, _VALUE_STATE_VISIBILITY_SHOWN);
                            self.context().$container.addClass(_CLASSNAME_DROPDOWN_CONTAINER_SHOWN);
                            _updateHint(self);
                            inside.core.js.executeSafe(callback)(self);
                        }
                );
            } // end of _slideIn

        ,   _slideOut = function(self) {
                _stateVisibility(self, _VALUE_STATE_VISIBILITY_HIDING);
                self.context().$container.removeClass(_CLASSNAME_DROPDOWN_CONTAINER_SHOWN);

                self.context().$dropdown.animate(
                        { 'marginTop': -self.context().$container.outerHeight() + 'px' }
                    ,   mosaic.settings().animationDelay
                    ,   'swing'
                    ,   function() {
                            _stateVisibility(self, _VALUE_STATE_VISIBILITY_HIDDEN);
                            self.context().$container.css({
                                    'display'   : 'none'
                                ,   'height'    : 0
                            });
                        }
                );
            } // end of _slideOut

        ,   _fadeIn = function(self, callback) {
                _stateVisibility(self, _VALUE_STATE_VISIBILITY_SHOWING);

                self.context().$container.css({
                        'display'   : 'block'
                    ,   'width'     : 0
                    ,   'height'    : 0
                    ,   'opacity'   : 0
                });

                self.context().$dropdown.css({
                        'marginTop' : 0
                });

                self.context().$container.animate(
                        {
                                'width'     :   _settings(self).width
                            ,   'height'    :   self.context().$dropdown.outerHeight()
                                            +   _PADDING_FOR_SHADOW
                                            +   'px'
                            ,   'opacity' : 1
                        }
                    ,   mosaic.settings().animationDelay
                    ,   'swing'
                    ,   function() {
                            _stateVisibility(self, _VALUE_STATE_VISIBILITY_SHOWN);
                            self.context().$container.addClass(_CLASSNAME_DROPDOWN_CONTAINER_SHOWN);
                            _updateHint(self);
                            inside.core.js.executeSafe(callback)(self);
                        }
                );
            } // end of _fadeIn

        ,   _fadeOut = function(self) {
                _stateVisibility(self, _VALUE_STATE_VISIBILITY_HIDING);
                self.context().$container.removeClass(_CLASSNAME_DROPDOWN_CONTAINER_SHOWN);

                self.context().$container.animate(
                        {
                                'width'   : 0
                            ,   'height'  : 0
                            ,   'opacity' : 0
                        }
                    ,   mosaic.settings().animationDelay
                    ,   'swing'
                    ,   function() {
                            _stateVisibility(self, _VALUE_STATE_VISIBILITY_HIDDEN);
                            self.context().$container.css({
                                    'display'   : 'none'
                            });
                        }
                );
            } // end of _fadeOut

        ,   _show = function(self, callback) {
                var
                        offset = _settings(self).getOffset
                    ;
                if (offset) { _moveTo(self, offset(self)); }

                // fix bu with auto-scroll when hint is added
                self.context().$container.get(0).scrollLeft = 0;

                if (_SETTINGS_VALUE_ANIMATION_TYPE_FADE == _settings(self)[_SETTINGS_ANIMATION_TYPE]) {
                    _fadeIn(self, callback);
                } else {
                    _slideIn(self, callback);
                }
            } // end of _show

        ,   _hide = function(self) {
                _focus(self, _NO_FOCUSED_ITEM);

                self.context().$empty.removeClass(_CLASSNAME_EMPTY_RESULTS_VISIBLE);

                // fix bu with auto-scroll when hint is added
                self.context().$container.get(0).scrollLeft = 0;

                if (_SETTINGS_VALUE_ANIMATION_TYPE_FADE == _settings(self)[_SETTINGS_ANIMATION_TYPE]) {
                    _fadeOut(self);
                } else {
                    _slideOut(self);
                }
            } // end of _hide

            // move dropdown window to offset specified relative to the document body
        ,   _moveTo = function(self, offset) {
                var
                        $now    = self.context().$container.parent()
                    ;

                while (!inside.core.util.is_positioned($now)) { $now = $now.parent(); }
                offset.left     -= $now.offset().left;
                offset.top      -= $now.offset().top;
                self.context().$container.css({
                        'left'  : offset.left + 'px'
                    ,   'top'   : offset.top + 'px'
                });
            } // end of _moveTo

        ,   _hidden = function(self) {
                var
                        state = _stateVisibility(self)
                    ;

                return null == state || _VALUE_STATE_VISIBILITY_HIDDEN == state;
            } // end of _hidden

        ,   _hiding = function(self) {
                return _VALUE_STATE_VISIBILITY_HIDING == _stateVisibility(self);
            } // end of _hiding

        ,   _showing = function(self) {
                return _VALUE_STATE_VISIBILITY_SHOWING == _stateVisibility(self);
            } // end of _showing

        ,   _shown = function(self) {
                return _VALUE_STATE_VISIBILITY_SHOWN == _stateVisibility(self);
            } // end of _shown

        ,   _isFocused = function(self) {
                return _stateFocused(self).length > 0;
            } // end of _isFocused

        ,   _focus = function(self, $liItem) {
                // mark current focused element as unfocused
                _stateFocused(self).removeClass(_DROPDOWN_FOCUSED_ITEM_CLASSNAME);

                // clear or set focus-item and mark new focused element as focused
                _stateFocused(self, $liItem).addClass(_DROPDOWN_FOCUSED_ITEM_CLASSNAME);

                if ($liItem.length) {
                    if ($liItem.position().top < 0) {
                        self.context().$dropdown.get(0).scrollTop += $liItem.position().top;
                    }
                    if ($liItem.position().top >= self.context().$dropdown.height()) {
                        self.context().$dropdown.get(0).scrollTop += $liItem.outerHeight();
                    }
                    if (1 == self.context().$dropdown.get(0).scrollTop % self.context().$dropdown.height()) {
                        self.context().$dropdown.get(0).scrollTop -= 1;
                    }
                }

                _updateHint(self);

                if ($liItem.length) {
                    inside.core.js.executeSafe(_settings(self).onfocus)(self, $liItem, _dataOf($liItem));
                }

                return $liItem;
            } // end of _focus

        ,   _focusNext = function(self) {
                var $focused = _stateFocused(self).next();
                while ($focused.length && _dataOf($focused).disabled) {
                    $focused = $focused.next();
                }
                // empty $focused object will be treaten as "clear focus"
                if ($focused.length) { _focus(self, $focused); }
            } // end of _focusNext

        ,   _focusPrev = function(self) {
                var $focused = _stateFocused(self).prev();
                while ($focused.length && _dataOf($focused).disabled) {
                    $focused = $focused.prev();
                }
                if ($focused.length) { _focus(self, $focused); }
            } // end of _focusPrev

        ,   _focusPageUp = function(self) {
                for (var i=0; i<_pageSize(self); i++) {
                    _focusPrev(self);
                }
            } // end of _focusNext

        ,   _focusPageDown = function(self) {
                for (var i=0; i<_pageSize(self); i++) {
                    _focusNext(self);
                }
            } // end of _focusPrev

        ,   _pageSize = function(self) {
                return Math.ceil(self.context().$dropdown.height() / parseInt(self.context().$dropdown.css('lineHeight')));
            } // end of _pageSize

        ,   _showHint = function(self) {
                self.context().$hint
                        .addClass(_CLASSNAME_HINT_VISIBLE)
                        .removeClass(_CLASSNAME_HINT_INVISIBLE)
                        // todo: more accurate, not just .text(). Possibly, use .data()
                        .html(_hintOf(_stateFocused(self)))
                        .css('top', _stateFocused(self).position().top + 'px')
                        ;
            } // end of _showHint

        ,   _hideHint = function(self) {
                self.context().$hint
                        .addClass(_CLASSNAME_HINT_INVISIBLE)
                        .removeClass(_CLASSNAME_HINT_VISIBLE)
                        .css('top', 0)
                        ;
            } // end of _hideHint

        ,   _updateHint = function(self) {
                _shown(self)
                        && _settings(self)[_SETTINGS_ENABLE_HINT]
                        && _isFocused(self)
                        && (_stateFocused(self).outerWidth() > self.context().$dropdown.width()
                         || (_forceHintOf(_stateFocused(self)) || _settings(self)[_SETTINGS_ALWAYS_SHOW_HINT]) && _hintOf(_stateFocused(self)).length
                        )
                            ? _showHint(self)
                            : _hideHint(self)
                            ;
            } // end of _updateHint

        ,   _$items = function(self) {
                return self.context().$dropdown.find('li');
            } // end of _$items

        // --- end of PRIVATE LOCAL METHODS ---
        ;




    // --- LOCAL EVENT HANDLERS ---

    // any event handling attached to particular widget
    // should be processed in routines below.
    // Apply() method just sets pointers to these handlers.

    var
            // select item in dropdown box by mouse click
            _trySelectByClick = function(self, e) {
                var
                        $item = $(e.target).closest('.' + _DROPDOWN_ITEM_CLASSNAME)
                    ;
                if ($item.size() && !_dataOf($(e.target).closest('.' + _DROPDOWN_ITEM_CLASSNAME)).disabled) {
                    _select(self, $item);
                }
            } // _trySelectByClick

            // check exiting from dropdown box through clicking outside
        ,   _checkHideByClick = function(self, e) {
                if (_shown(self) && null == _container(e.target)) {
                    var
                            preventHideOnClick = _settings(self).preventHideOnClick
                        ;
                    if (!preventHideOnClick || !preventHideOnClick(self, e)) {
                        _cancel(self, {});
                    }
                }
            } // _checkHideByClick

            // focus on item inside dropdown box by mouse move
        ,   _tryFocusByMouseMove = function(self, e) {
                if (self.context().$dropdown.get(0) == e.target) { return undefined; }

                var $liItem = $(e.target).closest('.' + _DROPDOWN_ITEM_CLASSNAME);
                if ($liItem.length) {
                    if (_dataOf($liItem).disabled) { return undefined; }
                }

                _focus(self, $liItem);
            } // _tryFocusByMouseMove

            // handle moving focus by arrow keys
            // and selecting item by enter.
            // Widget should be shown to proceed. This is checked inside
            // outer event handler.
        ,   _handleFocusAndSelectByKeys = function(self, e) {
                // should be focused before
                if (!_isFocused(self)) { return undefined; }

                if (inside.code.KEY_UP == e.which) {
                    _focusPrev(self);
                } else if (inside.code.KEY_DOWN == e.which) {
                    _focusNext(self);
                } else if (inside.code.KEY_PAGE_UP == e.which) {
                    _focusPageUp(self);
                } else if (inside.code.KEY_PAGE_DOWN == e.which) {
                    _focusPageDown(self);
                } else if (inside.code.KEY_ENTER == e.which) {
                    _select(self, _stateFocused(self));
                }
            } // _handleFocusAndSelectByKeys

            // Widget should be shown to proceed. This is checked inside
            // outer event handler.
        ,   _handleCancelByESC = function(self, e) {
                inside.code.KEY_ESC == e.which ? _cancel(self, { 'ESC':true }) : void(0);
            } // _handleCancelByESC

            // Prevents default browser behavior when user presses keys.
            // Widget should be shown to proceed. This is checked inside
            // outer event handler.
        ,   _preventDefaults = function(self, e) {
                if (inside.code.KEY_UP == e.which) {
                    e.preventDefault(); return false;
                } else if (inside.code.KEY_DOWN == e.which) {
                    e.preventDefault(); return false;
                } else if (inside.code.KEY_ENTER == e.which) {
                    e.preventDefault(); return false;
                } else if (inside.code.KEY_ESC == e.which) {
                    e.preventDefault(); return false;
                }

                return undefined;
            }

        // --- end of LOCAL EVENT HANDLERS ---
        ;




    // --- STATE KEYS ---

    var
            // key for storing settings
            _STATE_KEY_SETTINGS             = 'mosaic-inputs-dropdown-settings'

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

        ,   _stateVisibility = function(self, stateValue) {
                return _state(self, _KEY_STATE_VISIBILITY, stateValue);
            } // end of _stateVisibility

            // should always return jQuery-object, possibly 0-size
        ,   _stateFocused = function(self, stateValue) {
                // if focused is not set then try to find it and set by jQuery
                if (undefined == stateValue && undefined == _state(self, _KEY_FOCUSED_ITEM)) {
                    _state(
                            self
                        ,   _KEY_FOCUSED_ITEM
                        ,   self.context().$dropdown.find('.' + _DROPDOWN_FOCUSED_ITEM_CLASSNAME)
                        ,   undefined
                    );
                }

                return _state(self, _KEY_FOCUSED_ITEM, stateValue, _NO_FOCUSED_ITEM);
            } // end of _stateFocused

        ;   // --- end of STATE FUNCTIONS ---




    // --- SETTINGS KEYS ---

    var
            // enable hint
            _SETTINGS_ENABLE_HINT           = 'hint'

        ,   _SETTINGS_WIDTH                 = 'width'

        ,   _SETTINGS_VISIBLE_ITEMS_COUNT   = 'visibleItemsCount'

        ,   _SETTINGS_ANIMATION_TYPE        = 'animationType'

        ,   _SETTINGS_ALWAYS_SHOW_HINT      = 'alwaysShowHint'

        ;   // --- end of SETTINGS KEYS ---




    // --- SETTINGS VALUES ---

    var

            _SETTINGS_VALUE_ANIMATION_TYPE_SLIDE    = 'slide'

        ,   _SETTINGS_VALUE_ANIMATION_TYPE_FADE     = 'fade'

        ;   // --- end of SETTINGS KEYS ---




    // --- DEFAULT SETTINGS ---

    var

            _defaultSettings = function() {
                var defaults = {};

                defaults[_SETTINGS_ENABLE_HINT]         = true;
                defaults[_SETTINGS_WIDTH]               = '300px';
                defaults[_SETTINGS_VISIBLE_ITEMS_COUNT] = 10;
                defaults[_SETTINGS_ANIMATION_TYPE]      = _SETTINGS_VALUE_ANIMATION_TYPE_SLIDE;
                defaults[_SETTINGS_ALWAYS_SHOW_HINT]    = false;

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
                        if (_SETTINGS_WIDTH == key) {
                            _width(self, settings[key]);
                        } else if (_SETTINGS_VISIBLE_ITEMS_COUNT == key) {
                            _visible(self, settings[key]);
                        }

                        _settings[key] = settings[key];
                    }

                    _stateSettings(self, _settings);
                }

                return _settings;
            } // end of _settings

        ,   _width = function(self, value) {
                self.context().$container.css({
                        'width': value
                });
            } // end of _width

                // sets amount of visible items in the dropdown list
        ,   _visible = function(self, value) {
                self.context().$dropdown.css({
                        'height'    :   value * parseInt(self.context().$dropdown.css('lineHeight'))
                                    +   'px'
                });
            } // end of _visible

        ;   // --- end of SETTINGS FUNCTIONS ---





    // --- SELECTORS ---

    var
            _CLASSNAME_HINT                     = 'mosaic-widgets-dropdown-hint'

        ,   _CLASSNAME_HINT_INVISIBLE           = 'mosaic-widgets-dropdown-hint-invisible'

        ,   _CLASSNAME_HINT_VISIBLE             = 'mosaic-widgets-dropdown-hint-visible'

        ,   _CLASSNAME_DROPDOWN_CONTAINER_SHOWN = 'mosaic-widgets-dropdown-container-shown'

        ,   _CLASSNAME_EMPTY_RESULTS            = 'mosaic-widgets-dropdown-empty-results'

        ,   _CLASSNAME_EMPTY_RESULTS_VISIBLE    = 'mosaic-widgets-dropdown-empty-results-visible'

        ;   // --- end of SELECTORS ---




    // --- SELECTOR FUNCTIONS ---

    var
            _getHintSelector = function() {
                return '.' + _CLASSNAME_HINT;
            } // end of _getHintSelector

        ,   _getEmptyResultsSelector = function() {
                return ['.', _CLASSNAME_EMPTY_RESULTS].join('');
            } // end of _getEmptyResultsSelector

        ;   // --- end of SELECTOR FUNCTIONS ---




    // --- HTML MAKERS ---

    //var

        //;   // --- end of HTML MAKERS ---




    // --- GLOBAL EVENT HANDLERS ---

    $(document).click(function(e) {
        // place your code for event handling like this
    });

    // --- end of GLOBAL EVENT HANDLERS ---




})(jQuery);
