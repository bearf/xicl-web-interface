// --- mosaic.widgets.scrollpane ---
// version of sep 20, 2011.

(function($) {




    // --- PRE-PHASE ---

    if (!window.inside) { throw('global object [inside] not found'); }
    if (!window.mosaic) { throw('global object [mosaic] not found'); }
    if (!window.mosaic.widgets) { throw('global object [mosaic.widgets] not found'); }

    if (window.mosaic.widgets.scrollpane) { return false; }

    // --- end of PRE-PHASE ---




    // --- PSEUDO-CONSTRUCTOR ---

    // This function creates context containing instance-specific
    // variables like idNamespace and any jQuery-elements inside widget.
    // Also it creates public object representing widget itself on the base
    // of "_proto" object. Then it create "context()" method returning
    // for this widget. Finally it returns created widget object itself.

    // idScrollpane is point to HTML-instance of the widget on the page.
    // It should point to:
    //      - id/name of the central DOM element of widget (input field in case of inputs);
    //      - any DOM element inside widget;
    //      - any jQuery-object inside widget.

    window.mosaic.widgets.scrollpane = function(idNamespace) {
        var
                _context        = {}
            ,   _constructor    = function() {  }
            ,   _self
            ;

        // saving id
        _context.idScrollpane   = idNamespace;

        // search of container for widget
        // any jQuery-object fields should start from "$" prefix
        _context.$container     = _container(idNamespace); if (!_context.$container) { return null; }

        // init all fields here
        _context.$scrollpane    = _context.$container;

        _context.$content       = function() {
            var element =
                    document
                        .getElementById(
                            _context.$scrollpane.attr('id')
                        ).firstChild;
                        /*.getElementsByClassName(_CLASSNAME_CONTENT_CONTAINER)
                        [0];*/

            // for Webkit
            while (element && element.className != _CLASSNAME_CONTENT_CONTAINER) {
                element = element.nextSibling;
            }

            return element;
        };

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




    // --- PUBLIC LOCAL METHODS  ---

    // This is prototype of any instance of widget-object
    // with public methods.

    var _proto = {

            // Must be invoked only at first time!
            // This method applies settings, makes some initialization
            // and then sets up event handlers binded to this particular
            // widget by ".bind(this)". All event handlers are functions
            // from LOCAL EVENTS HANDLERS section.
            apply: function(settings) {
                // apply settings to the element
                _settings(this, settings);

                // apply scrollbars if needed
                this.redo();

                window.setInterval(function() {
                    if (_stateContentSize(this) != _getContentSize(this)) {
                        this.redo();
                    }
                }.bind(this), _RECALC_INTERVAL);

                $(window).resize(function() {
                    this.redo();
                }.bind(this));

                // apply event handling to element like this,
                // using .bind(this):
                //this.context().$some_element.click(function(e) { _localEventHandler(this, e); }.bind(this));

                return this;
            } // end of apply

            // apply scrollbars if needed
        ,   redo: function() {
                if (_needScroll(this)) {
                    if (!_hasScroll(this)) { _addScroll(this); }
                    _recalcScroll(this)
                } else if (_hasScroll(this)) {
                    _removeScroll(this);
                }

                _stateContentSize(this, _getContentSize(this));

                return this;
            } // end of redo

            // shortcut to private "_settings"
        ,   settings: function(settings) {
                return _settings(this, settings);
            } // end of settings

            // ANY OTHER METHODS HERE

    };

    // --- end of PUBLIC LOCAL METHODS  ---




    // --- PRIVATE CONSTS ---

    var
            _ZERO                           = 'mosaic-widgets-scrollpane-const-ZERO'

        ,   _RECALC_INTERVAL                = 100

            // any string const used as key to inner data values
            // should start from "namespace-name"-key.
        ,   _KEY_SOMEDATA                   = 'exampleNamespace-key-string-const'

            // one possible value for _KEY_SOMEDATA
        ,   _VALUE_SOMEDATA_VALUE_FIRST     = 200

        ,   _KEY_CONTENT_SIZE               = 'mosaic-widgets-scrollpane-content-size'

        ,   _KEY_DRAG_START_POSITION        = 'mosaic-widgets-scrollpane-drag-start-position'

        ,   _KEY_DRAG_START_SCROLLLEFT      = 'mosaic-widgets-scrollpane-drag-start-scrollleft'

        ,   _KEY_DRAG_START_POINT           = 'mosaic-widgets-scrollpane-drag-start-point'

            // key for storing dragging state
        ,   _KEY_DRAGGING                   = 'mosaic-widgets-scrollpane-drag-start-dragging'

            // key for storing settings
        ,   _KEY_SETTINGS                   = 'mosaic-widgets-scrollpane-key-settings'

        ,   // width
            _KEY_SETTINGS_WIDTH             = 'width'

            // container for content
        ,   _CLASSNAME_CONTENT_CONTAINER    ='mosaic-widgets-scrollpane-content'

            // scrollpane container
        ,   _CLASSNAME_SCROLL_CONTAINER     = 'mosaic-widgets-scrollpane-scroll-container'

            // scrollpane track
        ,   _CLASSNAME_SCROLL_TRACK         = 'mosaic-widgets-scrollpane-scroll-track'

            // scrollpane drag
        ,   _CLASSNAME_SCROLL_DRAG          = 'mosaic-widgets-scrollpane-scroll-drag'

        ,   _CLASSNAME_SCROLL_DRAG_ACTIVE   = 'mosaic-widgets-scrollpane-scroll-drag-active'


        // --- end of PRIVATE CONSTS ---
        ;




    // --- PRIVATE GLOBAL METHODS ---

    // These methods are invoked in global context of all widgets
    // with no touch to concrete instances.

    var
            // Find container for given namespace.
            // Finds object with id/name=idNamespace and then
            // its closest parent with selector = ".container-for-idNamespace-id-object".
            _container = function(idNamespace) {
                return inside.core.util.object(idNamespace);
            } // end of _container

        ,   _getContentContainerSelector = function() {
                return '.' + _CLASSNAME_CONTENT_CONTAINER;
            } // end of _getContentSelector

        ,   _getScrollContainerSelector = function() {
                return '.' + _CLASSNAME_SCROLL_CONTAINER;
            } // end of _getScrollContainerSelector

        ,   _getScrollTrackSelector = function() {
                return '.' + _CLASSNAME_SCROLL_TRACK;
            } // end of _getScrollTrackSelector

        ,   _getScrollDragSelector = function() {
                return '.' + _CLASSNAME_SCROLL_DRAG;
            } // end of _getScrollDragSelector

        // --- end of PRIVATE GLOBAL METHODS ---
        ;




    // --- PRIVATE LOCAL METHODS ---

    // Each of these methods exists as one istance,
    // but it gets "self" object as first parameter
    // which represents conrete instance of widget.
    // Since, each of these methods executes in local
    // context of widget

    var
            // method for retrieving or setting inner property of namespace
            // binded to concrete instance on the page
            _state = function(self, name, value) {
                if (undefined != value) {
                    self.context().$container.data(
                            name
                        ,   0 == value ? _ZERO : value
                    );
                }

                return  _ZERO == self.context().$container.data(name)
                            ? 0
                            : self.context().$container.data(name);
            } // end of _state

        ,   _stateContentSize = function(self, value) {
                return _state(self, _KEY_CONTENT_SIZE, value);
            } // end of _stateContentSize

        ,   _stateDragging = function(self, value) {
                return _state(self, _KEY_DRAGGING, value);
            } // end of _dragging

        ,   _stateDragPoint = function(self, value) {
                return _state(self, _KEY_DRAG_START_POINT, value);
            } // end of _startDragPoint

        ,   _stateDragStartPosition = function(self, value) {
                return _state(self, _KEY_DRAG_START_POSITION, value);
            } // end of _stateDragStartPosition

        ,   _stateDragStartScrollLeft = function(self, value) {
                return _state(self, _KEY_DRAG_START_SCROLLLEFT, value);
            } // end of _stateDragStartScrollLeft

            // check whether scroll is needed
        ,   _needScroll = function(self) {
                return _getContentSize(self) > _getContainerSize(self);
            } // end of _needScroll

            // check whether is scroll attached to panel
        ,   _hasScroll = function(self) {
                return _$getScrollContainer(self).size() > 0;
            } // end of _hasScroll

            // add scroll controls to HTML
        ,   _addScroll = function(self) {
                self.context().$container.append(
                        '<div class="' + _CLASSNAME_SCROLL_CONTAINER + '">'
                    +       '<div class="' + _CLASSNAME_SCROLL_TRACK + '">'
                    +           '<span class="' + _CLASSNAME_SCROLL_DRAG + '">&nbsp;</span>'
                    +       '</div>'
                    +   '</div>'
                );

                _$getScrollDrag(self).mousedown(function(e) {
                    // prevent text selection in Webkit
                    return _startDrag(self, { 'left': e.pageX, 'top': e.pageY });
                });

                // todo: detach
                $(document).mousemove(function(e) {
                    if (!_stateDragging(self)) { return; }

                    // returns false to prevent text selection in IE
                    return _drag(self, { 'left': e.pageX, 'top': e.pageY });
                });

                // todo: detach
                $(document).mouseup(function(e) {
                    _endDrag(self);
                });

                _$getScrollContainer(self).click(function(e) {
                    if ($(e.target).closest(_getScrollDragSelector()).size()) { return; }

                    var
                            delta           = e.pageX - _$getScrollTrack(self).offset().left
                        ,   dragPosition    = delta - _getDragSize(self)
                        ;

                    _scrollDragTo(self, dragPosition);
                });
            } // end of _addScroll

            // calculate scroll size and other variables
        ,   _recalcScroll = function(self) {
                _$getScrollDrag(self)
                        .css({
                                'width' : _getDragSize(self) + 'px'
                            ,   'left'  : 0
                        });
                self.context().$content().scrollLeft = 0;
            } // end of _recalcScroll
            // add scroll controls to HTML

            // remove scroll controls from HTML
        ,   _removeScroll = function(self) {
                _$getScrollContainer(self).remove();
            } // end of _removeScroll

        ,   _getContainerSize = function(self) {
                return self.context().$container.width();
            } // end of _getContainerSize

        ,   _getContentSize = function(self) {
                return self.context().$content().scrollWidth;
            } // end of _getContentSize

        ,   _getTrackSize = function(self) {
                return _$getScrollTrack(self).width();
            } // end of _$getTrackSize

        ,   _getDragSize = function(self) {
                return Math.ceil(
                    _getContainerSize(self) / _getContentSize(self) * _getTrackSize(self)
                );
            } // end of _getDragSize

        ,   _startDrag = function(self, point) {
                _stateDragging(self, true);
                _stateDragPoint(self, point);
                _stateDragStartPosition(self, parseInt(_$getScrollDrag(self).css('left')));
                _stateDragStartScrollLeft(self, self.context().$content().scrollLeft);

                _$getScrollDrag(self).addClass(_CLASSNAME_SCROLL_DRAG_ACTIVE);

                // prevent text selection in webkit
                return false;
            } // end of _startDrag

        ,   _drag = function(self, point) {
                var
                        delta                   = point.left - _stateDragPoint(self).left
                    ,   newDragPosition         = _stateDragStartPosition(self) + delta
                    ;

                _scrollDragTo(self, newDragPosition);

                // prevent selection in IE
                return false;
            } // end of _drag

            // move scroll drag to specified position.
            // move content accordingly.
        ,   _scrollDragTo = function(self, px) {
                var
                        minDragPosition         = 0
                    ,   maxDragPosition         = _getTrackSize(self) - _getDragSize(self)
                    ,   newDragPosition         = Math.max(Math.min(px, maxDragPosition), minDragPosition)
                    ,   maxScrollLeftPosition   = _getContentSize(self) - _getContainerSize(self)
                    ,   newScrollLeftPosition   = Math.round(
                            maxScrollLeftPosition * px / maxDragPosition
                        )
                    ;

                _$getScrollDrag(self).css(
                        'left'
                    ,   newDragPosition + 'px'
                );

                // todo: move into inner state
                self.context().$content().scrollLeft = newScrollLeftPosition;
            }

        ,   _endDrag = function(self) {
                _stateDragging(self, false);

                _$getScrollDrag(self).removeClass(_CLASSNAME_SCROLL_DRAG_ACTIVE);
            } // end of _endDrag

        // --- end of PRIVATE LOCAL METHODS ---
        ;




    // --- HTML GETTERS ---

    var

            _$getScrollContainer = function(self) {
                return self.context().$scrollpane.find(_getScrollContainerSelector())
            } // end of _$getScrollContainer

        ,   _$getScrollTrack = function(self) {
                return self.context().$container.find(_getScrollTrackSelector());
            } // end of _$getScrollContainer

        ,   _$getScrollDrag = function(self) {
                return self.context().$container.find(_getScrollDragSelector());
            } // end of _$getScrollDrag

        // --- end of HTML GETTERS ---
        ;




    // --- SETTINGS ---

    var
            _settings = function(self, settings) {
                var
                        _settings = _stateSettings(self)
                    ;
                _settings = 'undefined' != typeof _settings ? _settings : {};

                if (settings) {
                    for (var key in settings) {
                        _settings[key] = settings[key];

                        if (_KEY_SETTINGS_WIDTH == key) {
                            _width(self, settings[key]);
                        }
                    }

                    _stateSettings(self, _settings);
                }

                return _settings;
            } // end of _settings

            // method for setting or retrieving settings of namespace
        ,   _stateSettings = function(self, settingsValue) {
                return _state(self, _KEY_SETTINGS, settingsValue);
            } // end of _state_settings

            // set width
        ,   _width = function(self, params) {
                self.context().$scrollpane.css(
                        'width'
                    ,   params
                );
            } // end of _width

        // --- end of SETTINGS SETTERS ---
        ;




})(jQuery);