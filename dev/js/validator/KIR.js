// ***********************************
// KIR object definition
// ***********************************

if (window.KIR) {exit;} // only ONE KIR object must be created

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

    // public properties and methods
    return {
        init : function() {
            //alert('window.KIR.init(): under construction');
        }, // end of init
    
        // adds log entry
        // adds end-of-line after
        log : function(message) {
            var formatted = message + '<br />\n';
            f_log += formatted;
            jQuery('#KIR-log').append(formatted);
        },
        
        show_log : function() {
        }, // end of show_log
        
        hide_log : function() {
        }, // end of hide_log
    
        // getter for log
        get_log : function() {
            return f_log;
        } // end of get_log
    }; // end of public properties and methods
}(); // this function's call create object
// end of KIR object definition
