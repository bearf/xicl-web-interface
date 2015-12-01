// ***********************************
// KIR object definition
// ***********************************

jQuery(document).ready(function() {

if (window.KIR) {return;} // only ONE KIR object must be created

// start of KIR object's definition
// we employ Douglas Crockford's pattern to create objects
// with "public" and "private" properties and methods
window.KIR = function() {
    // private properties
    var
        f_log = '' // log
        ;
    // end of private properties

    // private methods
    // end of private methods

    // create function-object
    var _self = function(element) {
        return KIR.core.namespace(element);
    }; // end of creating function-object

    // public properties and methods
    _self.init = function() {
        //alert('window.KIR.init(): under construction');
    }; // end of init

    // adds log entry
    // adds end-of-line after
    _self.log = function(message) {
        var formatted = message + '<br />\n';
        f_log += formatted;
        jQuery('#KIR-log').append(formatted);
    };

    _self.show_log = function() {
    }; // end of show_log

    _self.hide_log = function() {
    }; // end of hide_log

    // getter for log
    _self.get_log = function() {
        return f_log;
    }; // end of get_log
    // end of public properties and methods

    return _self;
}(); // this function's call create object
// end of KIR object definition

});