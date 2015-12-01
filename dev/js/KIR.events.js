// --- KIR.events ---
// version of oct 10, 2011

// ***********************************
// servers for handling four non-standard events:
//  - typing: occurs if users unpressed the key and some time limit is elapsed
//  - paste: occurs if paste command was processed
//  - cut: occurs if cut command was processed
//  - change: occurs if control's value was changed by keyboard or context menu
//  - blur: occurs if control's losts focus
//  - click: self-explaining
//  - a4jsubmit: try of submit form via A4J
//  - a4jcomplete: A4J request successfully completed
// ***********************************
// note: KIR.events processes AFTER onclick and other handlers
// ***********************************
// presumed events order:
//  1. change
//  2. paste
//  3. cut
//  4. typing
// ***********************************
//  todo: check on html radiogroup
// ***********************************
// bug: change on html select doesn't work in Chrome and Safari
// ***********************************

// process after document is loaded
jQuery(function($) {

if (!KIR) { // KIR object must be created before
    alert('KIR object is not found!');
    return;
}

if (KIR.events) { return; } // only one KIR.events object must be created




    // --- PRIVATE GLOBAL METHODS ---

    var

            _tryCreateEvtNamespace = function(namespace) {
                if (!namespace.evt || !namespace.evt.oldValue) {
                    (function() {

                        var oldValue = namespace.control && namespace.control.get_value ? namespace.control.get_value() : '';

                        namespace.evt = {

                                'oldValue' : function(value) {
                                    if ('undefined' != typeof value) {
                                        oldValue = value;
                                    } else {
                                        return oldValue;
                                    }
                                } // end of oldValue

                        }; // end of evt

                    })();
                }
            } // end of _tryCreateEvtNamespace

        ;   // --- end of PRIVATE GLOBAL METHODS ---




    // --- PROTOTYPE (PUBLIC GLOBAL METHODS) ---

    var

            _proto = {

                    settings: function(settings) {
                        return _settings(settings);
                    } // end of settings

            }

        ; // --- end of PROTOTYPE (PUBLIC GLOBAL METHODS) ---




    // --- SETTINGS KEYS  ---

    var
            // hint
            _SETTINGS_TYPING_TIMEOUT    = 'typingTimeout'

        ;   // --- end of SETTINGS KEYS ---





    // --- DEFAULT SETTINGS ---

    var

            _defaultSettings = function() {
                var defaults = {};

                defaults[_SETTINGS_TYPING_TIMEOUT]  = 100;

                return defaults;
            } // end of _defaultSettings

        ;   // --- end of DEFAULT SETTINGS ---




    // --- GLOBAL SETTINGS VALUES ---

    var

            _settingsValues = _defaultSettings()

        ;   // --- end of GLOBAL SETTINGS VALUES ---




    // --- SETTINGS FUNCTIONS ---

    var

            _settings = function(settings) {
                // todo: clone!
                var _settings = _settingsValues;

                if (settings) {
                    for (var key in settings) {
                        //if (_SETTINGS_HINT == key) {
                        //    _hint(self, settings[key]);
                        //}

                        _settings[key] = settings[key];
                    }

                    _settingsValues = _settings;
                }

                return _settingsValues;
            } // end of _settings

        ;   // --- end of SETTINGS FUNCTIONS ---




// start of KIR.events object's definition
// we employ Douglas Crockford's pattern to create objects
// with "public" and "private" properties and methods
KIR.events = function() {
    // private const
    var
            PASTE_CUT_TIMEOUT = 10
        
        ,   MESSAGE_ERROR_OCCURED_WHILE_PROCESSING_EVENT_HANDLER = 'An error occured while processing an event handler'
        ;
    // end of private const
    
    // private methods
    var
            _scheduleTyping = function(self, namespace, e) {
                if (namespace.typing) { clearTimeout(namespace.typing); }
                e.typing_value = namespace.control.get_value();
                namespace.typing = setTimeout(function() {
                    namespace.events.raise(KIR.events.EVENT_TYPING, e);
                }, self.settings()[_SETTINGS_TYPING_TIMEOUT]);
            }

        ,   _ignoreControlKeys = function(keyCode) {
                if (37 == keyCode) { return true; } // LEFT
                if (39 == keyCode) { return true; } // RIGHT
                if (38 == keyCode) { return true; } // UP
                if (40 == keyCode) { return true; } // DOWN
                if (34 == keyCode) { return true; } // PAGE DOWN
                if (33 == keyCode) { return true; } // PAGE UP
                if (36 == keyCode) { return true; } // HOME
                if (35 == keyCode) { return true; } // END

                return 27 == keyCode; // ESC
            }

        ,process_dom_events = function(self, html, namespace, e) {
            // get the type of event
            var type = e.type;
            // case for event types
            if ('keyup' == type || 'keydown' == type) {
                // if raising of CHANGE is successful then we assume that value was really changed 
                // and we should start invocation of new change
                // if CHANGE is failed then previous TYPING must be processed
                (function(keyCode) {
                    // ignore TYPING/CHANGE for arrow/page/home/end keys
                    if ('SELECT' != html.tagName.toUpperCase() && _ignoreControlKeys(keyCode)) { return undefined; }

                    if (namespace.events.raise(KIR.events.EVENT_CHANGE, e)) {
                        _scheduleTyping(self, namespace, e);
                    }
                })('undefined' != e.which ? e.which : e.keyCode);
            } else if ('paste' == type || 'cut' == type) {
                // support only one paste/cut event at a moment
                if (namespace[type]) { clearTimeout(namespace[type]); }
                // save old value;
                var old_value = namespace.control.get_value();
                // register timeout to allow value be changed
                // anonymous function checks whenether value was really changed or not
                // todo: remove old_value                
                namespace[type] = setTimeout(function() {
                    if (old_value != namespace.control.get_value()) {
                        namespace.events.raise(KIR.events.EVENT_CHANGE, e);
                        if ('paste' == type) {
                            namespace.events.raise(KIR.events.EVENT_PASTE, e);
                        } else {
                            namespace.events.raise(KIR.events.EVENT_CUT, e);
                        }
                        _scheduleTyping(self, namespace, e);
                    }
                }, PASTE_CUT_TIMEOUT);
            } else if ('click' == type && 'SELECT' == html.tagName.toUpperCase()) {
                namespace.events.raise(KIR.events.EVENT_CHANGE, e);
                namespace.events.raise(KIR.events.EVENT_CLICK, e);
            } else if ('keydown' == type && 'SELECT' == html.tagName.toUpperCase()) {
                namespace.events.raise(KIR.events.EVENT_CHANGE, e);
            } else if ('click' == type && 'INPUT' == html.tagName.toUpperCase() && 'checkbox' == html.type) {
                namespace.events.raise(KIR.events.EVENT_CHANGE, e);
                namespace.events.raise(KIR.events.EVENT_CLICK, e);
            } else if ('click' == type) {
                namespace.events.raise(KIR.events.EVENT_CLICK, e);
            } else if ('blur' == type) {
                namespace.events.raise(KIR.events.EVENT_BLUR, e);
            }
        } // end of process_dom_events

        // main event handler of whole class,
        // serves as a single entry point for all event handlers
        ,handle_dom_events = function(self, e) {
            // get event object, it's always present
            e = KIR.DOM.get_real_event(e);
            // get the source of event
            var dom = KIR.DOM.get_real_event_target(e);
            // check for CLICK and OPTION: option produces click too
            if ('click' == e.type && 'OPTION' == dom.tagName.toUpperCase()) { dom = dom.parentNode; }
            // get namespace of HTML
            var namespace = KIR(dom);
            // handle events for "owner" of namespace
                // what is 'owner'?
                // it's a pointer to OWNER namespace of this object
                // example:
                // html input I inside calendar C has its' namespace N1
                // but it belongs to calendar object with namespace N2
                // so we have the pointer I.N1.owner = N2
                // and the pointer C.N2.html = I
            process_dom_events(self, namespace.owner.html, namespace.owner, e);
            // if owner is not html dom itself then process events for it
            if (namespace.owner != namespace) { process_dom_events(self, namespace.html, namespace, e); }
        } // end of handle_dom_events
        
        // cross-browser function for attaching events
        ,w3c_attach = function(html, handler, event) {
            if (html.addEventListener) {
                html.addEventListener(event, handler, false);
            } else if (html.attachEvent){
                html.attachEvent('on'+event, handler);
            }         
        } // end of attach
        
        ,get_updated_elements = function(request) {
            // get ids of elements
            var ids = request.getResponseHeader("Ajax-Update-Ids");
            // and convert them into array
            return KIR.utils.array(
                KIR.core.convert(ids)
            );
        } // end of get_updated_elements
        
        // bind oncomplete handlers into Richfaces
        ,bind_oncomplete = function() {
            A4J.AJAX.popQueue = function(request) {
                // our changes in original code
                if (!request._aborted) {
                    // send updated elements
                    // todo: CHECK document!
                    KIR.events(document).raise(KIR.events.EVENT_A4J_COMPLETE, null, get_updated_elements(request));
                } // end of our changes
                if (request.shouldNotifyQueue && request.queue) {
                    request.queue.pop();
                }
            }; // end of oncomplete binding
        } // end of bind_oncomplete

        // bind onsubmit handlers into Richfaces .3.3.2SR1
        ,bind_onsubmit = function() {
            A4J.AJAX.Submit = function(formId, event, options) {
                // our changes in original code
                // fires only if formId is present
                // if handlers return false then break the submissions
                if (formId && !KIR.events(formId).raise(KIR.events.EVENT_A4J_SUBMIT, event)) {
                    return false;
                } // end of our changes
                var domEvt = A4J.AJAX.CloneEvent(event);
                var query = A4J.AJAX.PrepareQuery(formId, domEvt, options);
                if (query) {
                    var queue = A4J.AJAX.EventQueue.getOrCreateQueue(options, formId);
                    if (queue) {
                        queue.push(query, options, domEvt);
                    } else {
                        A4J.AJAX.SubmitQuery(query, options, domEvt);
                    }
                }
                return false;
            }; // end of binding onsubmit
        } // end of bind_onsubmit

        // this function creates event interface for KIR interface
        //  - namespace: HTML DOM element
        ,create = function(namespace) {




            // --- PSEUDO-CONSTRUCTOR ---

            // create object with public properties and methods
            // _self becomes one more link to this inside event handlers
            var _self = (function(namespace) {
                var
                        _constructor = function() {  }
                    ,   _self
                    ;

                _constructor.prototype = _proto;
                _self = new _constructor();

                _self.namespace   = namespace;
                _self.html        = namespace.html;

                return _self;
            })(namespace); // --- end of PSEUDO-CONSTRUCTOR




            // --- PUBLIC LOCAL METHODS ---

            // raise particular user-defined event and call all handlers
            // returns false:
            //  - if CHANGE is occured but not real change of input was produced
            //  - if any handler has returned false; all handlers are processed whether it's case or not
            _self.raise = function(event, e, params) {
                    // block change if no difference since last raising
                    // and indicate that raising is failed
                    _tryCreateEvtNamespace(_self.namespace); // check for evt namespace
                    if (KIR.events.EVENT_CHANGE == event) {
                        if (_self.namespace.evt.oldValue() == _self.namespace.control.get_value()) {
                            return false;
                        } else {
                            _self.namespace.evt.oldValue(_self.namespace.control.get_value());
                        }            
                    }
                    // create empty params if not present
                    if (!params) params = {};
                    // if no event handlers then ignore handlers
                    // but we must return true because change was actually produced
                    if (!f_handlers[event] || f_handlers[event].is_empty()) { return true; }
                    // return-value
                    var result = true;
                    // call all handlers on element
                    for (var i=0; i<f_handlers[event].size(); i++) {
                        try {
                            // todo: optimize: too many invokations
                            var value = f_handlers[event].get(i)(KIR.DOM.get_real_event(e), _self.namespace, params);
                            if ('boolean' == typeof value) { result = result && value; }
                        } catch(exception) {
                            KIR.log(MESSAGE_ERROR_OCCURED_WHILE_PROCESSING_EVENT_HANDLER);
                        }
                    }
                    // raising is completed successfully
                    return result;
            }; // end of raise
        
            // shortcut for handle event of typing
            _self.typing = function(handler, _attach) {
                    // check for attach flag and then attach or detach handler
                    _attach = 'undefined' == typeof _attach || 'boolean' == typeof _attach && _attach;
                    if (_attach) { attach(handler, KIR.events.EVENT_TYPING); }
                    else { detach(handler, KIR.events.EVENT_TYPING); }
                    // jQuery-style return
                    return _self;
            }; // end of typing
        
            // shortcut for handle event of paste via context menu or Ctrl+V
            _self.paste = function(handler, _attach) {
                    // check for attach flag and then attach or detach handler
                    _attach = 'undefined' == typeof _attach || 'boolean' == typeof _attach && _attach;
                    if (_attach) { attach(handler, KIR.events.EVENT_PASTE); }
                    else { detach(handler, KIR.events.EVENT_PASTE); }
                    // jQuery-style return
                    return _self;
            }; // end of paste
        
            // shortcut for handle event of cut via context menu or Ctrl+X
            _self.cut = function(handler, _attach) {
                    // check for attach flag and then attach or detach handler
                    _attach = 'undefined' == typeof _attach || 'boolean' == typeof _attach && _attach;
                    if (_attach) { attach(handler, KIR.events.EVENT_CUT); }
                    else { detach(handler, KIR.events.EVENT_CUT); }
                    // jQuery-style return
                    return _self;
            }; // end of cut
        
            // shortcut for handle event of change
            _self.change = function(handler, _attach) {
                    // check for attach flag and then attach or detach handler
                    _attach = 'undefined' == typeof _attach || 'boolean' == typeof _attach && _attach;
                    if (_attach) { attach( handler, KIR.events.EVENT_CHANGE); }
                    else { detach(handler, KIR.events.EVENT_CHANGE); }
                    // jQuery-style return
                    return _self;
            }; // end of change
        
            // shortcut for handler event of click
            _self.click = function(handler, _attach) {
                    // check for attach flag and then attach or detach handler
                    _attach = 'undefined' == typeof _attach || 'boolean' == typeof _attach && _attach;
                    if (_attach) { attach(handler, KIR.events.EVENT_CLICK); }
                    else { detach(handler, KIR.events.EVENT_CLICK); }
                    // jQuery-style return
                    return _self;
            }; // end of click
        
            // shortcut for handler event of blur
            _self.blur = function(handler, _attach) {
                    // check for attach flag and then attach or detach handler
                    _attach = 'undefined' == typeof _attach || 'boolean' == typeof _attach && _attach;
                    if (_attach) { attach(handler, KIR.events.EVENT_BLUR); }
                    else { detach(handler, KIR.events.EVENT_BLUR); }
                    // jQuery-style return
                    return _self;
            }; // end of blur
        
                // shortcut for handler event of a4j submitting form
                // you can prevent submitting by returning false from your handler
            _self.a4jsubmit = function(handler, _attach) {
                    // check for attach flag and then attach or detach handler
                    _attach = 'undefined' == typeof _attach || 'boolean' == typeof _attach && _attach;
                    if (_attach) { attach(handler, KIR.events.EVENT_A4J_SUBMIT); }
                    else { detach(handler, KIR.events.EVENT_A4J_SUBMIT); }
                    // jQuery-style return
                    return _self;
            }; // end of a4jsubmit
        
            // --- end of PUBLIC LOCAL METHODS ---
    



            // --- PUBLIC SPECIFIC LOCAL METHODS ---

            // shortcut for handler event of a4j completing
            if (document == _self.html) {
                _self.a4jcomplete = function(handler, _attach) {
                    // check for attach flag and then attach or detach handler
                    _attach = 'undefined' == typeof _attach || 'boolean' == typeof _attach && _attach;
                    if (_attach) { attach(handler, KIR.events.EVENT_A4J_COMPLETE); }
                    else { detach(handler, KIR.events.EVENT_A4J_COMPLETE); }
                    // jQuery-style return
                    return _self;
                }; 
            } // end of a4jcomplete

            // --- end of PUBLIC SPECIFIC LOCAL METHODS



        
            // --- PRIVATE FIELDS ---

            var

                    f_handlers = {}

                ;   // --- end of PRIVATE FIELDS ---




            // --- PRIVATE LOCAL METHODS ---

            var
                // attach unstandard event to element
                attach = function(handler, event) {
                    // handler with "self" attached
                    var domHandler = function(e) {
                        handle_dom_events(this, e);
                    }.bind(_self);
                    // init particular event array if absent
                    if (!f_handlers[event]) { f_handlers[event] = new KIR.utils.Stack(); }
                    // push handler into stack
                    if (!f_handlers[event].contains(handler)) { f_handlers[event].push(handler); }
                    // get html
                    var html = _self.namespace.html;
                    // attach w3c onkeyup if typing or change
                    if (KIR.events.EVENT_TYPING == event || KIR.events.EVENT_CHANGE == event) { w3c_attach(html, domHandler, 'keyup'); }
                    // attach w3c onkeydown if typing
                    if (KIR.events.EVENT_TYPING == event || KIR.events.EVENT_CHANGE == event) { w3c_attach(html, domHandler, 'keydown'); }
                    // attach w3c paste if paste or change
                    if (KIR.events.EVENT_PASTE == event || KIR.events.EVENT_CHANGE == event) { w3c_attach(html, domHandler, 'paste'); }
                    // attach w3c cut if cut or change
                    if (KIR.events.EVENT_CUT == event || KIR.events.EVENT_CHANGE == event) { w3c_attach(html, domHandler, 'cut'); }
                    // click or change on select
                    if (KIR.events.EVENT_CLICK == event || (KIR.events.EVENT_CHANGE == event && html.tagName.toUpperCase() == 'SELECT')) { w3c_attach(html, domHandler, 'click'); }
                    // change on checkbox
                    if (KIR.events.EVENT_CHANGE == event && html.tagName.toUpperCase() == 'INPUT' && 'checkbox' == html.type) { w3c_attach(html, domHandler, 'click'); }
                    // blur
                    if (KIR.events.EVENT_BLUR == event) { w3c_attach(html, domHandler, 'blur'); }
                    // delete autocomplete
                    if (html.autocomplete) { html.setAttribute('autocomplete', 'off'); }
                    // set "old" value
                    // todo: troubles with elements without get_value method
                    _tryCreateEvtNamespace(_self.namespace);
                } // end of attach

                // detaches event handler
                // NOTE: function only removes handler from stack. All W3C event handlers are still working
                ,detach = function(handler, event) {
                    // exit if no handlers for particular event
                    if (!f_handlers[event]) { return; }
                    // and return!
                    f_handlers[event].remove(handler);
                } // end of detach

                ;   // --- end of PRIVATE LOCAL METHODS ---




            return _self;



    
        }; // end of create

    // --- end of PRIVATE METHODS??? ---




    // this is the CORE function of events
    // it gets:
    //  - html id/html name/html dom element - and after that returns object with basic events interface
    //  - empty param - returns KIR.events interface
    // note: if incorrect param is passed, the function returns KIR.events
    var _self = function(element) {
        // convert id/element/jquery to KIR namespace
        // todo: check for BODY
        var namespace = KIR(element);
        // if no param is passed we just return base interface
        if (!namespace) { return KIR.events; }
        // if no form/control object exists we create it
        if (!namespace.events) { namespace.events = create(namespace); }
        // and return
        return namespace.events;
    }; // end of CORE validation function
    $.extend(_self, _proto);




    // *** attach additional properties to _self ***
        // shortcuts for event codes
        _self.EVENT_TYPING = 'typing';
        _self.EVENT_PASTE = 'paste';
        _self.EVENT_CUT = 'cut';
        _self.EVENT_CHANGE = 'change';
        _self.EVENT_CLICK = 'click';
        _self.EVENT_BLUR = 'blur';
        _self.EVENT_A4J_COMPLETE = 'a4jcomplete';
        _self.EVENT_A4J_SUBMIT = 'a4jsubmit';
    // *** end of attaching additional properties to _self ***




    // *** attach additional methods to _self ***
    
    // perform some initialization:
    // if A4J.AJAX is defined then
    //  - replace popQueue method for processing oncomplete handlers
    //  - replace Submit method for processing onsubmit handlers
    //  - set parsing for controls all AJAX responses
    // ALARM: if Richfaces version has changed you are to check these methods in the
    // new implementation of Richfaces
    _self.init = function() {
        // if Richfaces exists
        if (window.A4J && window.A4J.AJAX) {
            bind_oncomplete();
            bind_onsubmit();
        } // end of checking for Richfaces

        // parse AJAX response for controls
        KIR.events(document).a4jcomplete(function(e, element, updated) {
            for (var i=0; i<updated.length; i++) {
                KIR.controls.parse(updated[i]);
            }
        }); // end of defining event handler for parsing of responses
        // jQuery-style return
        return _self;
    }; // end of init
        
    // *** end of attaching additional methods and properties *** 




    return _self;



    
}(); // this function's call create object
// end of KIR.events object definition




// perform initialization
KIR.events().init();




}); // end of jQuery(document).ready()