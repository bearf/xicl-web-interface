// ***********************************
// KIR.DOM object definition
// ***********************************
// DOM-specific util functions
// ***********************************

// process after document is loaded
jQuery(document).ready(function() {

if (!KIR) { // KIR object must be created before
    alert('KIR object is not found!');
    return;
}

if (KIR.DOM) { return; } // only one KIR.DOM object must be created

// start of KIR.DOM object's definition
// we employ Douglas Crockford's pattern to create objects
// with "public" and "private" properties and methods
KIR.DOM = function() {
    // private const
    var
            MESSAGE_TRYING_TO_GET_HASH_FOR_NULL_ELEMENT = 'Trying to get hash code for null-element'
        ,   MESSAGE_TRYING_TO_GET_HASH_FOR_NOT_HTML_DOM_ELEMENT = 'Trying to get hash for not HTML DOM element'
        ,   MESSAGE_FAILED_GET_TOP_CALLER = 'Fail in implementation of get_top_caller(). It\'s possible that JavaScript implementation was changed'
        ;
    // end of private const

    // private properties
    var
            f_hash_index = 0
        ;
    // end of private properties

    // private methods
    //var
    //    ;    
    // end of private methods

    // create object with public properties and methods
    // _self becomes one more link to this inside event handlers
    var _self = {
        // checks whether element is HTML DOM
        // 'document' is DOM too
        is_DOM: function(html_dom_element) { 
            // check for null
            if (!html_dom_element) { return false; }
            // check for document
            if (document == html_dom_element) { return true; }
            // check for "normal" browsers
            try {
                return HTMLElement && html_dom_element instanceof HTMLElement;
            } catch(e) {
                // for IE
            }
            // for IE
            return document == html_dom_element || html_dom_element.nodeType && 1 == html_dom_element.nodeType;
        } // end of is_DOM

        // get hash code for HTML element and sets it to one.
        // this code is unique until page is reloaded
        //  - html_dom_element: HTML DOM element
        ,hash: function(html_dom_element) {
            // check for null
            if (!html_dom_element) { throw(MESSAGE_TRYING_TO_GET_HASH_FOR_NULL_ELEMENT); }
            // check for HTML DOM
            if (!_self.is_DOM(html_dom_element)) { throw(MESSAGE_TRYING_TO_GET_HASH_FOR_NOT_HTML_DOM_ELEMENT); }
            // create hash if abset
            if (!html_dom_element.hash) { html_dom_element.hash = html_dom_element.tagName.toUpperCase() + (f_hash_index++); }
            // return it
            return html_dom_element.hash;
        } // end of hash

        // get top caller of any function
        ,get_top_caller: function() {
            try {
                // caller of get_top_caller
                var
                        caller = arguments.callee.caller
                    ,   last
                    ;
                // 1. go to the last not-null caller
                // 2. last: this is FF7 bugfix: caller.caller == caller at some stack level, when eval() is used
                while (caller.caller && last != caller) {
                    last = caller;
                    caller = caller.caller;
                }
                // and return
                return caller;
            } catch(exception) {
                // any exception means that JavaScript Implementation was changed
                throw(MESSAGE_FAILED_GET_TOP_CALLER);
            }
        } // end of get_top_caller

        // get real DOM event
        ,get_real_event: function(e) {
            // if e is passed then it's okay
            if (e) { return e; }
            // if window event is specified then return it
            if (window.event) { return window.event; }
            // another case - get top caller of all functions
            var top = _self.get_top_caller();
            // define function that extracts event from it
            // todo: memory leaks?
            function searcher() {
                // all browsers except IE define event handler as onclick(event) { ... }
                // alse event object has currentTarget property, we use it as a flag
                // to determine whether it' event or not
                // just take first argument's value and return
                if (1 == this.arguments.length && this.arguments[0].currentTarget) { return this.arguments[0]; }
                // other cases - no events
                return null;
            }
            // and apply in context of top caller
            return searcher.call(top);
        } // end of get_real_event

        // find target of the event
        ,get_real_event_target: function(e) {
            // get real event
            e = _self.get_real_event(e);
            // check for null
            if (!e) { return null; }
            // get target
            var target = e.target ? e.target : (e.srcElement ? e.srcElement : null);
            // defeat Safari bug
            if (target && target.nodeType == 3)  { target = target.parentNode; }
            // return target
            return target;
        } // end of get_event_target
    }; // end of creating object with public properties and methods
    
    return _self;
}(); // this function's call create object
// end of KIR.DOM object definition

// *** TESTS ***

}); // end of jQuery(document).ready()
