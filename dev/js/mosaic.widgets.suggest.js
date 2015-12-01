// --- mosaic.widgets.suggest ---
// version of sep 20, 2011

// later

// todo: bug: начинаем набирать, ошибаемся, стираем - suggestion не появляется снова
// todo: bug: начинаем набирать, уходим за левую границу курсора - suggestion не обновляется
// todo: bug: suggestion появляется на одно слово сзади при длинном тексте, который переносится по срокам
// todo: меньший размер
// todo: ловить уход suggestion за экран
// todo: opera: не позиционируется если нажать enter
// todo: IE8: неправильная позиция при нажатии клавиш
// todo: IE8: неправильная позиция при нажатии enter
// todo: IE7: неправильная позиция при нажатии клавиш
// todo: IE7: неправильная позиция при нажатии enter

(function($) {

    // --- PRE-PHASE ---

    if (!window.mosaic) { throw('global object [mosaic] not found'); }
    if (!window.mosaic.widgets) { throw('global object [mosaic.widgets not found'); }
    if (!window.mosaic.widgets.dropdown) { throw('global object [mosaic.widgets.dropdown not found'); }

    if  (window.mosaic.widgets.suggest) { return false; }

    // --- end of PRE-PHASE




    // --- PRIVATE GLOBAL FIELDS ---
    // any private field should start from "_" prefix

        //var

            // end of PRIVATE GLOBAL FIELDS ---
            //;




    // --- PSEUDO-CONSTRUCTOR ---
    // idSuggest points to:
    //      - id/name of the central DOM element of control
    //      - any DOM element inside control
    //      - any jQuery-object inside control

    window.mosaic.widgets.suggest = function(idSuggest) {
        var
                _context        = {}
            ,   _constructor    = function() {  }
            ,   _self
            ;

        // saving id
        _context.idSuggest      = idSuggest;

        // search of container for control
        // any jQuery-object fields should start from "$" prefix
        _context.$container     = _input(idSuggest); if (!_context.$container) { return null; }
        _context.$input         = _context.$container;

        // init all fields here
        // the line below is here for just demonstration purposes
        _context.dropdown       = mosaic.widgets.dropdown(_dropdownBoxId(idSuggest));

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
            suggest = window.mosaic.widgets.suggest

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

                _createCaretPosDiv(this);

                context.dropdown.settings({
                        'filter': function(selfDropdown, handler) {
                            inside.core.js.executeSafe(
                                    _settings(this).filter
                                ,   function(items) {
                                        items.length ? handler(items) : this.cancel();
                                    }.bind(this)
                            )(this);
                        }.bind(this)
                    ,   'onselect': function(selfDropdown, label, data) {
                            var
                                    caretPos    = inside.core.util.getCaretPos(context.$input)
                                ,   value       = this.context().$input.val()
                                ,   suffix      = label.substr(this.prefix().length)
                                ,   newCaretPos = caretPos + suffix.length
                                ;
                            context.$input.val(
                                    value.substring(0, caretPos)
                                +   suffix
                                +   value.substr(caretPos)
                            );
                            _appliedValue(this, context.$input.val());
                            _clearStartCaretPos(this);

                            inside.core.util.setCaretPos(context.$input, newCaretPos);
                        }.bind(this)
                    ,   'getOffset': function(selfDropdown) {
                            return _getOffset(this);
                        }.bind(this)
                    ,   'oncancel': function(selfDropdown) {
                            _clearStartCaretPos(this);
                        }.bind(this)
                    ,   'width': '400px' // default
                    ,   'visibleItemsCount': 5 // default
                    ,   'animationType': 'fade' // default
                });

                $('[id$=' + _dropdownBoxId(this.context().idSuggest) + ']')
                        .addClass('mosaic-widgets-suggest-container');

                $(function() {
                    KIR.events(context.idSuggest).typing(function(e, namespace, params) {
                        _handleUserTyping(this, e, namespace);
                    }.bind(this)).change(function(e, namespace, params) {
                        _tryMoveSuggestionBoxOnChange(this);
                    }.bind(this));

                    context.$input.keypress(function(e) {
                        if (inside.code.KEY_DOWN == e.keyCode && e.ctrlKey) { // todo: safe keyCode
                            this.call();
                        }
                    }.bind(this));
                }.bind(this));

                return this;
            } // end of apply

            // get prefix of currently edited word
        ,   prefix: function() {
                var
                        caretPos        = inside.core.util.getCaretPos(this.context().$input)
                    ,   position        = caretPos - 1
                    ,   value           = this.context().$input.val()
                    ;

                if (!_hasStartCaretPos(this) || _startCaretPos(this) >= caretPos) {
                    while (position > -1 && !inside.core.util.inSet(value.charAt(position), _BLANKS)) {
                        position--;
                    }
                    _startCaretPos(this, position + 1);
                }

                return value.substring(_startCaretPos(this), caretPos);
            } // end of prefix

            // shortcut to private "_settings"
        ,   settings: function(settings) {
                return _settings(this, settings);
            } // end of settings

        ,   call: function() {
                this.context().dropdown.call();
            } // call

        ,   cancel: function() {
                this.context().dropdown.cancel();
            } // cancel

            // ANY OTHER METHODS HERE

    };
    // --- end of PUBLIC LOCAL METHODS  ---




    // --- PUBLIC GLOBAL METHODS ---

    // --- end of PUBLIC GLOBAL METHODS ---




    // --- PRIVATE CONSTS ---

    var
            // tag to find in fake div when trying locate position of cursor
            // in input field
            _CARET_POSITION_MARKER_TAG      = 'strong'

            // marker to put in fake div
        ,   _CARET_POSITION_MARKER          = '<' + _CARET_POSITION_MARKER_TAG + '>&nbsp;</' + _CARET_POSITION_MARKER_TAG + '>'

            //
        ,   _CARET_POSITION_DIV_ID_SUFFIX   = 'CaretPositionDiv'

            // chars are bounding area where
            // suggest will be called
        ,   _BLANKS                         = [' ', '\n']

            // key for storing settings
        ,   _STATE_SETTINGS                 = 'state-suggest-settings'

        ,   // state: applied value
            _STATE_APPLIED_VALUE            = 'state-suggest-applied-value'

        ,   _KEY_START_CARET_POS            = 'key-suggest-start-caret-pos'

        ,   _NO_START_CARET_POS             = -1

        // --- end of PRIVATE CONSTS ---
        ;





    // --- PRIVATE GLOBAL METHODS ---

    var
            // find container for given namespace
            // finds object with id/name=idNamespace and then
            // its closest parent with selector = ".container-for-idNamespace-id-object"
            _input = function(idSuggest) {
                return inside.core.util.object(idSuggest, 'input, textarea')
            } // end of container

            // returns id for suggestion box attached to input
        ,   _dropdownBoxId = function(idSuggest) {
                return idSuggest + 'DropdownBox';
            }

        // --- end of PRIVATE GLOBAL METHODS ---
        ;




    // --- PRIVATE LOCAL METHODS ---

    var
            _settings = function(self, settings) {
                var
                        _settings = _stateSettings(self)
                    ;
                _settings = 'undefined' != typeof _settings ? _settings : {};

                if (settings) {
                    for (var key in settings) {
                        _settings[key] = settings[key];
                    }

                    _stateSettings(self, _settings);
                }

                return _settings;
            } // end of _settings

            // method for retrieving or setting inner property of namespace
            // binded to concrete instance on the page
        ,   _state = function(self, name, value) {
                if (undefined == value) { return self.context().$container.data(name); }
                    else { self.context().$container.data(name, value); }
            } // end of _state

            // method for setting or retrieving settings of namespace
        ,   _stateSettings = function(self, settingsValue) {
                return _state(self, _STATE_SETTINGS, settingsValue);
            } // end of _state_settings

        ,   _startCaretPos = function(self, value) {
                return _state(self, _KEY_START_CARET_POS, value);
            }

        ,   _clearStartCaretPos = function(self) {
                _startCaretPos(self, _NO_START_CARET_POS);
            }

        ,   _hasStartCaretPos = function(self) {
                return  _NO_START_CARET_POS != _startCaretPos(self)
                    &&  'undefined' != typeof _startCaretPos(self)
            }

            // last applied value
        ,   _appliedValue = function(self, appliedValue) {
                return _state(self, _STATE_APPLIED_VALUE, appliedValue);
            } // end of _stateAppliedValue

            // check whether suggestion is allowed
        ,   _allowed = function(self) {
                var
                        caretPos    = inside.core.util.getCaretPos(self.context().$input)
                    ,   value       = self.context().$input.val()
                    ,   length      = value.length
                    ;
                return  length == caretPos
                     || inside.core.util.inSet(value.charAt(caretPos), _BLANKS);
            }

            // get position of cursor in pixels inside of input
            // relative to the document
        ,   _getOffset = function(self) {
                var
                        caretPos    = inside.core.util.getCaretPos(self.context().$input)
                    ,   value       = self.context().$input.val()
                    ,   offset      = self.context().$input.offset()
                    ;
                value   =   value.substring(0, caretPos)
                        +   _CARET_POSITION_MARKER
                        +   value.substr(caretPos)
                        ;
                _caretPosDiv(self).innerHTML = value;

                var
                        offsetParent = $(_caretPosDiv(self)).find(_CARET_POSITION_MARKER_TAG).position()
                    ;

                offset.left += offsetParent.left;
                offset.top  += offsetParent.top;

                offset.top  += parseInt(self.context().$input.css('lineHeight'));
                offset.top  -= parseInt(self.context().$input.get(0).scrollTop);

                return offset;
            } // getOffset

            // create fake div to locate caret position
            // inside input
        ,   _createCaretPosDiv = function(self) {
                var
                        caretPosDiv     = document.createElement('div')
                    ;

                for (var key in self.context().$input.get(0).style) {
                    if ('function' != typeof self.context().$input.get(0).style[key]) {
                        try {
                            caretPosDiv.style[key] = self.context().$input.css(key);
                        } catch(e) {  } // prevent errors on writing readonly properties
                    }
                }

                caretPosDiv.id = _caretPosDivId(self);
                $(caretPosDiv).css({
                        'width'     : self.context().$input.width() + 'px'
                    ,   'position'  : 'absolute'
                    ,   'left'      : 0
                    ,   'top'       : 0
                    ,   'zIndex'    : -1
                    ,   'visibility': 'hidden'
                    ,   'fontSize'  : self.context().$input.css('fontSize')
                    ,   'whiteSpace': 'pre-wrap'
                });
                document.body.appendChild(caretPosDiv);

                return caretPosDiv;
            } // end of _createCaretPosDiv

            // return the fake div to find position of cursor
            // inside input
        ,   _caretPosDiv = function(self) {
                return document.getElementById(_caretPosDivId(self));
            } // caretPosDiv

            // this function returns id for fake div
        ,   _caretPosDivId = function(self) {
                return self.context().$input.attr('name') + _CARET_POSITION_DIV_ID_SUFFIX;
            } // caretPosDivId

        // --- end of PRIVATE LOCAL METHODS ---
        ;




    // --- LOCAL EVENT HANDLERS ---

    // any event handling attached to particular widget
    // should be processed in routines below.
    // Apply() method just sets pointers to these handlers.

    var
            // various reactions for user typing in input
            _handleUserTyping = function(self, e, namespace) {
                // handle need for showing:
                // value must be changed since last applying suggestion item
                // this checking must be done at upper "if"
                // to prevent calculating prefix and setting start caret pos
                if (self.context().dropdown.hidden() && self.context().$input.val() != _appliedValue(self)) {
                    // prefix must be not empty and valid
                    if (_allowed(self) && self.prefix().length) {
                        self.call();
                    }
                }

                // handle suggest reaction when user erases text in input:
                // suggest must show all variants
                if (self.context().dropdown.shown()) {
                    // prefix must be valid, and value must be changed
                    // since last applying suggestion item
                    if (_allowed(self) && self.context().$input.val() != _appliedValue(self)) {
                        self.call();
                    }
                }
            } // end of _handleUserTyping

            // if suggestion is shown then move it to cursor position
        ,   _tryMoveSuggestionBoxOnChange = function(self) {
                if (self.context().dropdown.shown()) {
                    self.context().dropdown.moveTo(_getOffset(self));
                }
            } // end of _tryMoveSuggestionBox

        // --- end of LOCAL EVENT HANDLERS ---
        ;

    // --- GLOBAL EVENT HANDLERS ---

    $(document).click(function(e) {
        // place your code for event handling like this
    });

    // --- end of GLOBAL EVENT HANDLERS ---

})(jQuery);