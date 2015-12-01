// ***********************************
// KIR.core object definition
// ***********************************

// process after document is loaded
jQuery(document).ready(function() {

if (!KIR) { // KIR object must be created before
    alert('KIR object is not found!');
    return;
}

if (KIR.core) { return; } // only one KIR.utils object must be created

// start of KIR.core object's definition
// we employ Douglas Crockford's pattern to create objects
// with "public" and "private" properties and methods
KIR.core = function() {
    // private const
    var
            MESSAGE_FAILED_EVALUATION = 'Value evaluation is failed'
            ,MESSAGE_NAMESPACE_EXPECTS_DOM_ELEMENT = 'Developer error: namespace could be created only for HTML DOM element'

            ,KIR_NAMESPACE_NAME = 'KIR_INTERNAL_NAMESPACE'
            ,FUNCTION_NAMES = [
                'alert(',
                'min(',
                'max('
                ]
        ;
    // end of private const

    // private properties
    //var
    //    ;
    // end of private properties

    // private methods
    var
        // checks whether value is set of javascript expressions or not
        check_javascript_expressions = function(value) {
            // check simple symbols
            if (-1 != value.search('[;\{\}]')) { return true; }
            // 
            if (-1 != value.search('[a-zA-Z_0-9]\\(')) { return true; }
            // check function names
            for (var i=0; i<FUNCTION_NAMES.length; i++) {
                if (-1 != value.indexOf(FUNCTION_NAMES[i])) { return true; }
            }
            return false;
        } // end of check_javascript_expressions

        // function that evaluates value
        //  - returns value if value is not string
        //  - returns evaluated value if value is string but not javascript code
        //  - returns function() { expressions; } if value if set of expressions
        //  - returns null in other cases
        //  - any exception turns into null-return value
        ,_evaluate = function(value) {
            try {
                // if not function then simply return the value
                if ('string' != typeof value) { return value; }
                // function name / function variable / numeric / boolean / string
                // if conversion is failed there then value is threated as just string
                try {
                    if (!check_javascript_expressions(value)) { return eval('(' + value + ')'); }
                } catch(exception) {
                    return value;
                }
                // expression kind is of 'function() { expressions; }'
                if (-1 != value.indexOf('function(')) { return eval('(' + value + ')'); }
                // expression kind is of 'expressions;'
                return eval('(function(){' + value +'})');
            } catch(exception) {
                KIR.log(MESSAGE_FAILED_EVALUATION);
            }
            // error cases
            return null;
        } // end of func
        
        // converts id/name/dom/jquery into HTML DOM element or array of HTML DOM elements
        // returns null if convertion fails
        // todo: naming rules
        ,_convert = function(element) {
            // if jquery 
            if ('object' == typeof element && element.each && element.get) { return element.get(0); }
            // html dom
            if (KIR.DOM.is_DOM(element)) { return element; } 
            // id
            if ('string' == typeof element && document.getElementById(element)) { return document.getElementById(element); }
            // name
            if ('string' == typeof element && 1 == jQuery('[name=' + element.replace(/:/g, "\\:") + ']').size()) { return jQuery('[name=' + element.replace(/:/g, "\\:") + ']').get(0); }
            // other cases we return null
            return null;
        } // end of convert
        
        // create KIR namespace for HTML DOM element
        //  - element: HTML DOM element
        //  if element is null then returns null
        //  if element is HTML DOM then returns namespace
        //  throws an exception if element is not HTML DOM element
        ,_namespace = function(element) {
            // if null then return null
            if (!element) { return null; }
            // check for HTML DOM, then create and return namespace
            if (!KIR.DOM.is_DOM(element)) { throw(MESSAGE_NAMESPACE_EXPECTS_DOM_ELEMENT); }
            if (!element[KIR_NAMESPACE_NAME]) { element[KIR_NAMESPACE_NAME] = {}; }
            return element[KIR_NAMESPACE_NAME];
        } // end of _namespace
        
        // filter all element(s) having specific KIR namespace
        ,_filter = function(element, namespace) {
            // if null then return null
            if (!element) { return null; }
            // check for HTML DOM, KIR and KIR.namespace
            if (!KIR.DOM.is_DOM(element)) { return null; }
            if (!element[KIR_NAMESPACE_NAME]) { return null; }
            if (!element[KIR_NAMESPACE_NAME][namespace]) { return null; }
            return element;
        } // end of filter

        ;    
    // end of private methods

    // create object with public properties and methods
    // _self becomes one more link to this inside event handlers
    var _self = {
        // converts id/name/dom/jquery/array/commalisr into HTML DOM element or array of HTML DOM elements
        // returns null if conversion fails
        convert: function(element) {
            return KIR.utils.map(element, _convert);
        } // end of convert
        
        // evaluate element value
        ,evaluate: function(element) {
            return KIR.utils.map(element, _evaluate);
        } // end of evaluate
        
        // create KIR namespace for html DOM element
        // element passed in as in convert so we invoke convert first
        ,namespace: function(element) {
            return KIR.utils.map(
                element,
                function(element) { return _namespace(_convert(element)); }
            );
        } // end of namespace
        
        // filter all element(s) having specific KIR namespace
        ,filter: function(element, namespace) {
            return KIR.utils.map(
                element,
                function(element) { return _filter(_convert(element), namespace); }
            );
        } // end of filter
    }; // end of creating object with public properties and methods
    
    return _self;
}(); // this function's call create object
// end of KIR.core object definition

// *** TESTS ***

}); // end of jQuery(document).ready()
