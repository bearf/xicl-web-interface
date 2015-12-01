// ***********************************
// KIR.events object definition
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
jQuery(document).ready(function() {

if (!KIR) { // KIR object must be created before
    alert('KIR object is not found!');
    return;
}

if (KIR.events) { return; } // only one KIR.events object must be created

// start of KIR.events object's definition
// we employ Douglas Crockford's pattern to create objects
// with "public" and "private" properties and methods
KIR.events = function() {
    // private const
    var
            TYPING_TIMEOUT = 500
        ,   PASTE_CUT_TIMEOUT = 10
        
        ,   MESSAGE_ERROR_OCCURED_WHILE_PROCESSING_EVENT_HANDLER = 'An error occured while processing an event handler'
        ;
    // end of private const

    // private properties
    //var
    //    ;
    // end of private properties

    // private methods
    var
        // main event handler of whole class,
        // serves as a single entry point for all event handlers
        handle_dom_events = function(e) {
            // get event object, it's always present
            e = KIR.DOM.get_real_event(e);
            // get the source of event
            var element = KIR.DOM.get_real_event_target(e);
            // get the type of event
            var type = e.type;
            // case for event types
            if ('keyup' == type || 'keydown' == type) {
                // if raising of CHANGE is successful then we assume that value was really changed 
                // and we should start invocation of new change
                // if CHANGE is failed then previous TYPING must be processed
                if (_self.raise(element, _self.EVENT_CHANGE, e)) {
                    if (element.typing) { clearTimeout(element.typing); };
                    e.typing_value = KIR.core.namespace(element).control.get_value();
                    element.typing = setTimeout(function() {
                        _self.raise(element, _self.EVENT_TYPING, e);
                    }, TYPING_TIMEOUT);
                }
            } else if ('paste' == type || 'cut' == type) {
                // support only one paste/cut event at a moment
                if (element[type]) { clearTimeout(element[type]); }
                // save old value;
                var old_value = KIR.core.namespace(element).control.get_value();
                // register timeout to allow value be changed
                // anonymous function checks whenether value was really changed or not
                // todo: remove old_value                
                element[type] = setTimeout(function() {
                    if (old_value != KIR.core.namespace(element).control.get_value()) {
                        _self.raise(element, _self.EVENT_CHANGE, e);
                        if ('paste' == type) {
                            _self.raise(element, _self.EVENT_PASTE, e);
                        } else {
                            _self.raise(element, _self.EVENT_CUT, e);
                        }
                    }
                }, PASTE_CUT_TIMEOUT);
            } else if ('click' == type && 'SELECT' == element.tagName.toUpperCase()) {
                _self.raise(element, _self.EVENT_CHANGE, e);
                _self.raise(element, _self.EVENT_CLICK, e);
            } else if ('click' == type && 'OPTION' == element.tagName.toUpperCase()) { // option produces click too
                _self.raise(element.parentNode, _self.EVENT_CHANGE, e);
                _self.raise(element.parentNode, _self.EVENT_CLICK, e);
            } else if ('click' == type && 'INPUT' == element.tagName.toUpperCase() && 'checkbox' == element.type) {
                _self.raise(element, _self.EVENT_CHANGE, e);
                _self.raise(element, _self.EVENT_CLICK, e);
            } else if ('click' == type) {
                _self.raise(element, _self.EVENT_CLICK, e);
            } else if ('blur' == type) {
                _self.raise(element, _self.EVENT_BLUR, e);
            }
        } // end of handle_dom_events
        
        // attach unstandard event to element
        ,attach = function(element, handler, event) {   
            // init handlers array if absent
            if (!element.handlers) { element.handlers = {}; }
            // init particular event array if absent
            if (!element.handlers[event]) { element.handlers[event] = new KIR.utils.Stack(); }
            // push handler into stack
            if (!element.handlers[event].contains(handler)) { element.handlers[event].push(handler); }
            // attach w3c onkeyup if typing or change
            if (_self.EVENT_TYPING == event || _self.EVENT_CHANGE == event) { w3c_attach(element, handle_dom_events, 'keyup'); }
            // attach w3c onkeydown if typing
            if (_self.EVENT_TYPING == event) { w3c_attach(element, handle_dom_events, 'keydown'); }
            // attach w3c paste if paste or change
            if (_self.EVENT_PASTE == event || _self.EVENT_CHANGE == event) { w3c_attach(element, handle_dom_events, 'paste'); }
            // attach w3c cut if cut or change
            if (_self.EVENT_CUT == event || _self.EVENT_CHANGE == event) { w3c_attach(element, handle_dom_events, 'cut'); }
            // click or change on select
            if (_self.EVENT_CLICK == event || (_self.EVENT_CHANGE == event && element.tagName.toUpperCase() == 'SELECT')) { w3c_attach(element, handle_dom_events, 'click'); }
            // change on checkbox
            if (_self.EVENT_CHANGE == event && element.tagName.toUpperCase() == 'INPUT' && 'checkbox' == element.type) { w3c_attach(element, handle_dom_events, 'click'); }
            // blur
            if (_self.EVENT_BLUR == event) { w3c_attach(element, handle_dom_events, 'blur'); }
            // delete autocomplete
            if (element.autocomplete) { element.setAttribute('autocomplete', 'off'); }
            // set "old" value
            // todo: troubles with elements without get_value method
            // if element is document then namespace will return null
            if (KIR.core.namespace(element) && KIR.core.namespace(element).control) { element.old = KIR.core.namespace(element).control.get_value(); }
        } // end of attach
        
        // detaches event handler
        // NOTE: function only removes handler from stack. All W3C event handlers are still working
        ,detach = function(element, handler, event) {
            // exit if no handlers at all or no handlers for particular event
            if (!element.handlers) { return; }
            if (!element.handlers[event]) { return; }
            // and return!
            element.handlers[event].remove(handler);
        } // end of detach
        
        // cross-browser function for attaching events
        ,w3c_attach = function(element, handler, event) {
            if (element.addEventListener) {
                element.addEventListener(event, handler, false);
            } else if (element.attachEvent){
                element.attachEvent('on'+event, handler);
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
                    _self.raise(document, _self.EVENT_A4J_COMPLETE, null, get_updated_elements(request));
                } // end of our changes
                if (request.shouldNotifyQueue && request.queue) {
                    request.queue.pop();
                }
            }; // end of oncomplete binding
        } // end of bind_oncomplete

        // bind onsubmit handlers into Richfaces        
        ,bind_onsubmit = function() {
            A4J.AJAX.Submit = function(containerId, formId, event, options) {
                // our changes in original code
                // fires only if formId is present
                // if handlers return false then break the submissions
                if (formId && !_self.raise(document.getElementById(formId), _self.EVENT_A4J_SUBMIT, event)) {
                    return false;
                } // end of our changes
                var domEvt = A4J.AJAX.CloneEvent(event);
                var query = A4J.AJAX.PrepareQuery(containerId, formId, domEvt, options);
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
        ;
    // end of private methods

    // create object with public properties and methods
    // _self becomes one more link to this inside event handlers
    var _self = {
        // shortcuts for event codes
        EVENT_TYPING: 'typing' 
        ,EVENT_PASTE: 'paste'
        ,EVENT_CUT: 'cut'
        ,EVENT_CHANGE: 'change'
        ,EVENT_CLICK: 'click'
        ,EVENT_BLUR: 'blur'
        ,EVENT_A4J_COMPLETE: 'a4jcomplete'
        ,EVENT_A4J_SUBMIT: 'a4jsubmit'
        
        // perform some initialization:
        // if A4J.AJAX is defined then
        //  - replace popQueue method for processing oncomplete handlers
        //  - replace Submit method for processing onsubmit handlers
        //  - set parsing for controls all AJAX responses
        // ALARM: if Richfaces version has changed you are to check these methods in the
        // new implementation of Richfaces
        ,init: function() {
            // if Richfaces exists
            if (window.A4J && window.A4J.AJAX) {
                bind_oncomplete();
                bind_onsubmit();
            } // end of checking for Richfaces

            // parse AJAX response for controls
            _self.a4jcomplete(function(e, element, updated) {
                for (var i=0; i<updated.length; i++) {
                    KIR.controls.parse(updated[i]);
                }
            }); // end of defining event handler for parsing of responses
        } // end of init
        
        // raise particular user-defined event and call all handlers
        // returns false:
        //  - if CHANGE is occured but not real change of input was produced
        //  - if any handler has returned false; all handlers are processed whether it's case or not
        ,raise: function(element, event, e, params) {
            // block change if no difference since last raising
            // and indicate that raising is failed
            if (_self.EVENT_CHANGE == event) {
                if (element.old == KIR.core.namespace(element).control.get_value()) {
                    return false;
                } else {
                    element.old = KIR.core.namespace(element).control.get_value();
                }            
            }
            // create empty params if not present
            if (!params) params = {};
            // if no event handlers then ignore handlers
            // but we must return true because change was actually produced
            if (!element.handlers || !element.handlers[event] || element.handlers[event].is_empty()) { return true; }
            // return-value
            var result = true;
            // call all handlers on element
            for (var i=0; i<element.handlers[event].size(); i++) {
                try {
                    // todo: optimize: too many invokations
                    var value = element.handlers[event].get(i)(KIR.DOM.get_real_event(e), element, params);
                    if ('boolean' == typeof value) { result = result && value; }
                } catch(exception) {
                    KIR.log(MESSAGE_ERROR_OCCURED_WHILE_PROCESSING_EVENT_HANDLER);
                }
            }
            // raising is completed successfully
            return result;
        } // end of raise
        
        // shortcut for handle event of typing
        ,typing: function(element, handler, _attach) {
            // check for attach flag and then attach or detach handler
            _attach = 'undefined' == typeof _attach || 'boolean' == typeof _attach && _attach ? true : false;
            if (_attach) { attach(element, handler, _self.EVENT_TYPING); }
            else { detach(element, handler, _self.EVENT_TYPING); }
        } // end of typing
        
        // shortcut for handle event of paste via context menu or Ctrl+V
        ,paste: function(element, handler, _attach) {
            // check for attach flag and then attach or detach handler
            _attach = 'undefined' == typeof _attach || 'boolean' == typeof _attach && _attach ? true : false;
            if (_attach) { attach(element, handler, _self.EVENT_PASTE); }
            else { detach(element, handler, _self.EVENT_PASTE); }
        } // end of paste
        
        // shortcut for handle event of cut via context menu or Ctrl+X
        ,cut: function(element, handler, _attach) {
            // check for attach flag and then attach or detach handler
            _attach = 'undefined' == typeof _attach || 'boolean' == typeof _attach && _attach ? true : false;
            if (_attach) { attach(element, handler, _self.EVENT_CUT); }
            else { detach(element, handler, _self.EVENT_CUT); }
        } // end of cut
        
        // shortcut for handle event of change
        ,change: function(element, handler, _attach) {
            attach(element, handler, _self.EVENT_CHANGE);
            // check for attach flag and then attach or detach handler
            _attach = 'undefined' == typeof _attach || 'boolean' == typeof _attach && _attach ? true : false;
            if (_attach) { attach(element, handler, _self.EVENT_CHANGE); }
            else { detach(element, handler, _self.EVENT_CHANGE); }
        } // end of change
        
        // shortcut for handler event of click
        ,click: function(element, handler, _attach) {
            // check for attach flag and then attach or detach handler
            _attach = 'undefined' == typeof _attach || 'boolean' == typeof _attach && _attach ? true : false;
            if (_attach) { attach(element, handler, _self.EVENT_CLICK); }
            else { detach(element, handler, _self.EVENT_CLICK); }
        } // end of click
        
        // shortcut for handler event of blur
        ,blur: function(element, handler, _attach) {
            // check for attach flag and then attach or detach handler
            _attach = 'undefined' == typeof _attach || 'boolean' == typeof _attach && _attach ? true : false;
            if (_attach) { attach(element, handler, _self.EVENT_BLUR); }
            else { detach(element, handler, _self.EVENT_BLUR); }
        } // end of click
        
        // shortcut for handler event of a4j submitting form
        // you can prevent submitting by returning false from your handler
        ,a4jsubmit: function(form, handler, _attach) {
            // check for attach flag and then attach or detach handler
            _attach = 'undefined' == typeof _attach || 'boolean' == typeof _attach && _attach ? true : false;
            if (_attach) { attach(form, handler, _self.EVENT_A4J_SUBMIT); }
            else { detach(form, handler, _self.EVENT_A4J_SUBMIT); }
        } // end of a4jsubmit
        
        // shortcut for handler event of a4j completing
        ,a4jcomplete: function(handler, _attach) {
            // check for attach flag and then attach or detach handler
            _attach = 'undefined' == typeof _attach || 'boolean' == typeof _attach && _attach ? true : false;
            if (_attach) { attach(document, handler, _self.EVENT_A4J_COMPLETE); }
            else { detach(document, handler, _self.EVENT_A4J_COMPLETE); }
        } // end of a4jsubmit
        
        ,test: function() {
        }
    }; // end of creating object with public properties and methods
    
    return _self;
}(); // this function's call create object
// end of KIR.events object definition

// perform initialization
KIR.events.init();

// *** TESTS ***

KIR.events.test();

}); // end of jQuery(document).ready()
