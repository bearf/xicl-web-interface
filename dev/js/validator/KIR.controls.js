// ***********************************
// KIR.controls object definition
// todo: implement custom controls
// ***********************************

// process after document is loaded
jQuery(document).ready(function() {

if (!KIR) { // KIR object must be created before
    alert('KIR object is not found!');
    return;
}

if (KIR.controls) { return; } // only one KIR.controls object must be created

// start of KIR.controls object's definition
// we employ Douglas Crockford's pattern to create objects
// with "public" and "private" properties and methods
KIR.controls = function() {
    // private const
    var
            MESSAGE_MULTIPLE_INTERFACES_FOR_THE_SAME_ELEMENT = 'Two or more interfaces are registered for the same control'
        ,   MESSAGE_INVALID_ARGUMENT_FOR_FIND = 'parameter for find method must be convertable into HTML DOM'

        ,   COMMON_INTERFACE = {
                get_control_name:       function() { return null; }
                ,get_jquery_selector:   function() { return null; }
                ,check:                 function(html_dom_element) { return true; }
                ,init:                  function(html) { this.html = html; }
                ,get_value:             function() { return this.html.value; }
                ,is_allow_override:     function() { return false; }
                ,disable:               function(value) { if ('undefined' == typeof value) value = true; this.html.disabled = value; KIR.validator(this.html).validate(); this.effects(); }
                ,enable:                function(value) { if ('undefined' == typeof value) value = true; this.disabled = !value; KIR.validator(this.html).validate(); this.effects(); }
                ,is_disabled:           function() { return this.html.disabled; }
                ,valid:                 function() {}
                ,invalid:               function() {}
                ,effects:               function() { if (!this.is_disabled() && KIR.core.namespace(this.html).validator.is_required()) { jQuery(this.html).addClass('validation-required'); } else { jQuery(this.html).removeClass('validation-required'); } }
                ,get_html:              function() { return this.html; }
            }
        ;
    // end of private const

    // private properties
    var
            f_controls = {} // all available types of controls as a hash array 'name'=>interface
        ,   f_registered_jquery_selectors = ''
        ;
    // end of private properties

    // private methods
    var
        // copy all methods from interface to dom element
        // todo: use prototypes
        copy = function(dom_element, _interface) {
            for (var index in _interface) {
                dom_element[index] = _interface[index];
            }
        } // end of copy
    ;    
    // end of private methods

    // create object with public properties and methods
    // _self becomes one more link to this inside event handlers
    var _self = {
        // parse html element, find all controls and attach methods to them
        parse: function(element) {
            // try to convert
            element = KIR.core.convert(element);
            // if no element is passed in then or no conversion produced then fail
            if (!element) { return null; }
            // for all types of controls
            for (var index in f_controls) {
                var control = f_controls[index];
                // find all possible controls inside element and try to cast them
                jQuery(element).find(control.get_jquery_selector()).each(function() {
                    // if it's this control actually 
                    if (control.check(this)) {
                        // and it's not have KIR.control namespace yet then copy methods
                        var namespace = KIR.core.namespace(this);
                        if (!namespace.control || namespace.control.get_control_name() != control.get_control_name() && control.is_allow_override()) {
                            namespace.control = { KIR : namespace }; // todo: remove this link
                            copy(namespace.control, control);
                            namespace.control.init(this);
                        // if marker is different then we have two interface for the same control
                        } else if (namespace.control.get_control_name() != control.get_control_name() && !control.is_allow_override()) {
                            throw(MESSAGE_MULTIPLE_INTERFACES_FOR_THE_SAME_ELEMENT);
                        } 
                    }
                });
            }
        } // end of parse
        
        // register new control in the system
        ,register: function(control) {
            // create new object and populate it with old and new methods
            var obj = {};
            copy(obj, COMMON_INTERFACE);
            copy(obj, control);
            // add to hash array
            f_controls[obj.get_control_name()] = obj;
            // populate jquery selector string
            f_registered_jquery_selectors += 0 < f_registered_jquery_selectors.length ? ', ' : '';
            f_registered_jquery_selectors += obj.get_jquery_selector();
        } // end of register
        
        // find all registered controls in container
        // returns array of HTML DOM elements
        ,find: function(container) {
            // convert container and throw exception if container is incorrect
            container = KIR.core.convert(container);
            if (!container) { throw(MESSAGE_INVALID_ARGUMENT_FOR_FIND); }
            // else create and return all descendants
            var result = [];
            jQuery(container).find(f_registered_jquery_selectors).each(function() {
                if (KIR.core.namespace(this).control) { result.push(this); }
            });
            return result;
        } // end of find
    }; // end of creating object with public properties and methods
    
    return _self;
}(); // this function's call create object
// end of KIR.controls object definition

// *** REGISTRATION ***

KIR.controls.register({
    get_control_name:       function() { return 'input'; }
    ,get_jquery_selector:   function() { return 'input[type=text], input[type=password]'; }
});

KIR.controls.register({
    get_control_name:       function() { return 'textarea'; }
    ,get_jquery_selector:   function() { return 'textarea'; }
});

// we suppose that first option is always fictive
KIR.controls.register({
    get_control_name:       function() { return 'select'; }
    ,get_jquery_selector:   function() { return 'select'; }
    ,get_value:             function() { return 0 == this.html.selectedIndex ? null : this.html.value; }
});

KIR.controls.register({
    get_control_name:       function() { return 'checkbox'; }
    ,get_jquery_selector:   function() { return 'input[type=checkbox]'; }
    ,get_value:             function() { return this.html.checked.toString(); }
});

    // todo: use this.KIR everywhere
    KIR.controls.register({
        get_control_name:       function() { return 'calendar'; }
        ,get_jquery_selector:   function() { return 'table.rich-calendar-exterior'; }
        ,init:                  function(html) { this.html = document.getElementById(html.id + 'InputDate'); }
        ,is_allow_override:     function() { return true; }
        ,effects:               function() {
            jQuery(this.html).prev().filter('span.validation-required-marker').remove();
            if (!this.is_disabled() && this.KIR.validator.is_required()) {
                jQuery(this.html).before('<span class="validation-required-marker"></span>'); 
            }
        }
    });

    KIR.controls.register({
        get_control_name:       function() { return 'select-address'; }
        ,get_jquery_selector:   function() { return 'textarea.select-addr-output'; }
        ,is_allow_override:     function() { return true; }
        ,effects:               function() {
            if (!this.is_disabled() && KIR.core.namespace(this.html).validator.is_required()) {
                jQuery(this.html).parent().addClass('validation-required'); // td
            } else {
                jQuery(this.html).parent().removeClass('validation-required'); // td 
            }
        }
    });

// parse document
KIR.controls.parse(jQuery('body'));

// *** TESTS ***

}); // end of jQuery(document).ready()
