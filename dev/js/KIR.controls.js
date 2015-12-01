// --- KIR.controls ---
// version of nov 11, 2011

// ***********************************
// todo: implement custom controls
// todo: move controls definition to theirs xhtml, xcss, js etc
// ***********************************

// process after document is loaded
jQuery(function($) {

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

                    get_control_name: function() { return null; }

                ,   get_jquery_selector: function() { return null; }

                ,   check: function(html_dom_element) { return true; }

                    // what is 'html'?
                    // it's a pointer to HTML element of this object that contains value and handles events
                    // example:
                    // html input I inside calendar C has its' namespace N1
                    // but it belongs to calendar object with namespace N2
                    // so we have the pointer I.N1.owner = N2
                    // and the pointer C.N2.html = I
                ,   init_html: function(html) {
                        this.namespace.html = html;
                        this.val = function(self) {

                            var _val = function() {
                                return this.namespace.html.value;
                            }.bind(self);

                            _val.visible = function() {
                                return this.val();
                            }.bind(self);

                            _val.old = function() {
                                return undefined;
                            }.bind(self);

                            return _val;

                        }(this);
                    }

                ,   get_value: function() { return this.val(); }

                ,   is_allow_override: function() { return false; }

                ,   disable: function(value) {

                        if ('undefined' == typeof value) value = true;
                        this.namespace.html.disabled = value;

                        if (this.namespace.validator) { this.namespace.validator.validate(); }

                        this.effects();

                    }

                ,   enable: function(value) {

                        if ('undefined' == typeof value) value = true;
                        this.namespace.html.disabled = !value;

                        if (this.namespace.validator) { this.namespace.validator.validate(); }

                        this.effects();

                    }

                ,   toggle: function() { this.enable(this.is_disabled()); }

                ,   is_disabled: function() { return this.namespace.html.disabled; }

                ,   valid: function() {}

                ,   invalid: function() {}

                ,   effects: function() {

                        if (this.namespace.validator && this.namespace.validator.is_required()) {
                            jQuery(this.namespace.html).addClass('new-validation-required');
                        } else {
                            jQuery(this.namespace.html).removeClass('new-validation-required');
                        }

                    }

            } // COMMON_INTERFACE

        ; // end of private const

    // private properties
    var
            f_controls = {} // all available types of controls as a hash array 'name'=>interface
        ,   f_registered_jquery_selectors = ''

        ,   _reParsing  = false

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
                var
                        control = f_controls[index]
                    ,   parser  = function() {
                            // if it's this control actually
                            if (control.check(this)) {
                                // and it's not have KIR.control namespace yet then copy methods
                                var namespace = KIR(this);
                                if (!namespace.control || _reParsing || namespace.control.get_control_name() != control.get_control_name() && control.is_allow_override()) {
                                    namespace.control = { 'namespace' : namespace };
                                    copy(namespace.control, control);
                                    namespace.control.init_html(this);
                                    // what is 'owner'?
                                    // it's a pointer to OWNER namespace of this object
                                    // example:
                                    // html input I inside calendar C has its' namespace N1
                                    // but it belongs to calendar object with namespace N2
                                    // so we have the pointer I.N1.owner = N2
                                    // and the pointer C.N2.html = I
                                    KIR(this).owner = namespace;
                                // if marker is different then we have two interface for the same control
                                } else if (namespace.control.get_control_name() != control.get_control_name() && !control.is_allow_override()) {
                                    throw(MESSAGE_MULTIPLE_INTERFACES_FOR_THE_SAME_ELEMENT);
                                }
                            }
                        }
                    ;
                // find all possible controls inside element and try to cast them
                jQuery(element).find(control.get_jquery_selector()).each(parser);
                jQuery(element).filter(control.get_jquery_selector()).each(parser);
            }
        } // end of parse

        ,   reParse: function(element) {
                _reParsing = true; this.parse(element); _reParsing = false;
            } // end of reParse
        
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
                if (KIR(this).control) { result.push(this); } // todo: what is KIR(this)?
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

            ignore_first_option:   true

        ,   get_control_name:      function() { return 'select'; }

        ,   get_jquery_selector:   function() { return 'select'; }

        ,   init_html: function(html) {
                this.namespace.html = html;
                this.val = function(self) {

                    // Предполагается, что первую опцию в select надо игнорировать только если
                    // у нее не проставлен атрибут "value".
                    var _val = function() {
                        return  this.ignore_first_option
                                && 0 == this.namespace.html.selectedIndex
                                && !this.namespace.html.value.length
                                    ? null
                                    : this.namespace.html.value;
                    }.bind(self);

                    _val.visible = function() {
                        return  'undefined' == typeof this.val()
                                    ? undefined
                                    : this.namespace.html.options[this.namespace.html.selectedIndex].innerHTML
                                    ;
                    }.bind(self);

                    _val.old = function() {
                        return undefined;
                    }.bind(self);

                    return _val;

                }(this);
            }

    });

    KIR.controls.register({
        get_control_name:       function() { return 'checkbox'; }
        ,get_jquery_selector:   function() { return 'input[type=checkbox]'; }
        ,get_value:             function() { return this.namespace.html.checked.toString(); }
    });

    KIR.controls.register({
            get_control_name: function() { return 'calendar'; }

        ,   get_jquery_selector: function() { return 'table.rich-calendar-exterior'; }

        ,   init_html: function(html) {
                this.namespace.html = document.getElementById(html.id + 'InputDate');
                this.val = function(self) {

                    var _val = function() {
                        return this.namespace.html.value;
                    }.bind(self);

                    _val.visible = function() {
                        return this.val();
                    }.bind(self);

                    _val.old = function() {
                        return undefined;
                    }.bind(self);

                    return _val;

                }(this);
            }

        ,   is_allow_override: function() { return true; }

        ,   effects:  function() {
                jQuery(this.namespace.html).prev().filter('span.new-validation-required-marker').remove();
                if (!this.is_disabled() && this.namespace.validator.is_required()) {
                    jQuery(this.namespace.html).before('<span class="new-validation-required-marker"></span>');
                }
            }
    });

    KIR.controls.register({
        get_control_name:       function() { return 'select-address'; }
        ,get_jquery_selector:   function() { return 'textarea.select-addr-output'; }
        ,is_allow_override:     function() { return true; }
        ,valid:                 function() {
            jQuery(this.namespace.html).parent().removeClass('invalid');
        }
        ,invalid:               function() {
            jQuery(this.namespace.html).parent().addClass('invalid');
        }
        ,effects:               function() {
            if (!this.is_disabled() && KIR(this.namespace.html).validator.is_required()) {
                jQuery(this.namespace.html).parent().addClass('new-validation-required'); // td
            } else {
                jQuery(this.namespace.html).parent().removeClass('new-validation-required'); // td
            }
        }
    });

    KIR.controls.register({

            get_control_name: function() {
                return 'dim-finder';
            } // end of get_control_name

        ,   get_jquery_selector: function() {
                return '.dim-finder-container > input[type=text]';
            } // end of get_jquery_selector

        ,   init_html: function(html) {
                this.namespace.html = html;
                this.val = function(self) {

                    // Предполагается, что первую опцию в select надо игнорировать только если
                    // у нее не проставлен атрибут "value".
                    var _val = function() {
                        return $('[id$="' + this.namespace.html.id + 'Hidden"]').val();
                    }.bind(self);

                    _val.visible = function() {
                        return  $('[id$="' + this.namespace.html.id + 'ValueText"]').text();
                    }.bind(self);

                    _val.old = function() {
                        return undefined;
                    }.bind(self);

                    return _val;

                }(this);
            }

        ,   is_allow_override: function() {
                return true;
            } // end of is_allow_override
    });

    // parse document
    KIR.controls.parse(jQuery('body'));

}); // end of jQuery(document).ready()
