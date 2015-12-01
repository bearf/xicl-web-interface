// ***********************************
// KIR.utils object definition
// ***********************************

// process after document is loaded
jQuery(document).ready(function() {

if (!KIR) { // KIR object must be created before
    alert('KIR object is not found!');
    return;
}

if (KIR.utils) { return; } // only one KIR.utils object must be created

// start of KIR.utils object's definition
// we employ Douglas Crockford's pattern to create objects
// with "public" and "private" properties and methods
KIR.utils = function() {
    // private const
    var
            MESSAGE_STACK_MUST_BE_CALLED_WITH_NEW_OPERATOR = 'Developer error: Stack function must be invoked with "new" operator'
        ;
    // end of private const

    // private properties
    //var
    //    ;
    // end of private properties

    // private methods
    //var
    //    ;    
    // end of private methods

    // create object with public properties and methods
    // _self becomes one more link to this inside event handlers
    var _self = {
        // common converting to arrays
        //  - returns [] if element is null
        //  - returns [element] if single
        //  - returns element if array
        array: function(element) {
            if (!element) { return []; }
            if (element instanceof Array) { return element; }
            return [element];
        } // end of array
        
        // takes in element/array/commalist and function
        // applies function to all elements in list above
        // todo: all in-params through map
        ,map: function(element, func) {
            // returns null if no param passed in
            if (!element) { return element; }
            // returns element if no function passed in
            if (!func) { return element; }
            // if array
            if ('object' == typeof element && element instanceof Array) {
                var i = 0;
                while (i < element.length) {
                    element[i] = func(element[i]);
                    if (null == element[i] && i < element.length-1) { element[i] = element.pop(); } 
                    else if (null == element[i] && i == element.length-1) { element.pop(); } 
                    else { i++; }
                }
                return element;
            } // end of checking for Array
            // string of comma-separated strings
            // RECURSION!
            if ('string' == typeof element && -1 < element.indexOf(',')) { return KIR.utils.map(element.split(','), func); }
            // and final: apply function
            return func(element);
        } // end of map
        
        // constructor of Stack
        // todo: too complicated closure... very slow
        ,Stack: function() {
            // must be invoked with "new" operator
            // _self will be not equal to this in the case
            if (_self == this) { throw(MESSAGE_STACK_MUST_BE_CALLED_WITH_NEW_OPERATOR); }
            
            // our best pattern
            return function() {
                // private properties
                var
                        f_values = []
                    ;
                // end of private properties
                
                // public properties and methods
                var _self = {
                    // push item to stack
                    push: function(value) {
                        f_values.push(value);
                    } // end of push
                    
                    // pop top item from stack
                    ,pop: function() {
                        return f_values.pop();
                    } // end of pop
                    
                    // get item from stack by index
                    ,get: function(index) {
                        return index < f_values.length ? f_values[index] : null;
                    } // end of get
                    
                    // remove item from stack
                    ,remove: function(value) {
                        for (var i=0; i<f_values.length; i++) {
                            if (value == f_values[i]) { f_values.splice(i, 1); }
                        }
                    } // end of remove
                    
                    // remove for containing
                    ,contains: function(value) {
                        for (var i=0; i<f_values.length; i++) {
                            if (value == f_values[i]) { return true; }
                        }
                        return false;
                    } // end of contains
                    
                    // get top item
                    ,top: function(value) {
                        return 0 < f_values.length ? f_values[f_values.length-1] : null;
                    } // end of top
                    
                    // check for empty
                    ,is_empty: function(value) {
                        return 0 == f_values.length;
                    } // end of is_empty
                    
                    // empty the stack
                    ,empty: function() {
                        f_values = [];
                    } // end of empty
                    
                    // get size
                    ,size: function() {
                        return f_values.length;
                    } // end of size
                }; // end of public properties and methods
                
                // return Stack object
                return _self;
            }(); // end of our best pattern
        } // end of Stack constructor
    }; // end of creating object with public properties and methods
    
    return _self;
}(); // this function's call create object
// end of KIR.utils object definition

// *** TESTS ***

}); // end of jQuery(document).ready()
