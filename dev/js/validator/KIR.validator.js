// ***********************************
// KIR.validator object definition
//
// *** PREREQUISITES ***
//  - every input (including custom) must be inside the particular form
// --------------------------------
// todo:    check raise change for custom controls (calendar, select address)
// todo:    date and time validators
// todo:    predefined-messages: blanks for field names
// todo:    calendar: more
// todo:    dependent fields and their validation
// --------------------------------
// todo:    show messages after rerender
// todo:    when form is rerendered partially it's strong needed to update messages
// todo:    think, is it need to run validation after attaching/detaching validators and add comments on this issue
// --------------------------------
// todo:    KIR.core.namespace() -> KIR()
// todo:    KIRnamespace.control -> html.methods
// todo:    remove KIR.controls module
// todo:    this.html -> this
// todo:    check KIR namespace -> KIRnamespace
// todo:    use KIR.core.namespace everywhere if possible
// todo:    toggle_disabled()
// todo:    create get_html_element method in all interfaces!
// --------------------------------
// todo:    make jQuery-style for events()
// todo:    KIR.namespace events
// todo:    incorporate event interface into DOM
// todo:    store in DOM only data, not methods
// todo:    use prototypes instead of constructors (KIR.utils.Stack)
// todo:    garbage collect
// todo:    bug: if this is present then KIR.validator and KIR.validator() are synonyms
// todo:    is "array" function really needed
// --------------------------------
// todo:    lock and unlock
// todo:    messenger & signature
// todo:    a4jsubmit for submitters, not for form
// todo:    autocomplete
// todo:    more strong and safe integration with Richfaces
// --------------------------------
// todo:    delete unnecessary messages
// todo:    html radio
// todo:    threats with 'name' attribute for html radio
// todo:    try/catch for parsing AJAX
// ***********************************

// process after document is loaded
jQuery(document).ready(function() {

if (!KIR) { // KIR object must be created before
    alert('KIR object is not found!');
    return;
}

if (KIR.validator) { return; } // only one KIR.validator object must be created

// start of KIR.validator object's definition
// we employ Douglas Crockford's pattern to create objects
// with "public" and "private" properties and methods
KIR.validator = function() {
    // private const
    var
            MESSAGE_INVALID_ELEMENT = 'Element passed as parameter is invalid'
        ,   MESSAGE_VALIDATION_INTERFACE_ALREADY_EXISTS = 'Internal error: validation interface for element already exists in the hash'
        ,   MESSAGE_FORM_VALIDATION_INTERFACE_FOR_CONTROL_NOT_FOUND_IN_HASH = 'Internal error: requested form validation interface not found in hash'
        ,   MESSAGE_TRYING_TO_FIND_FORM_FOR_NOT_HTML_DOM_ELEMENT = 'Trying to find form for not HTML DOM element'
        ,   MESSAGE_ERROR_OCCURED_WHILE_PROCESSING_VALID_CONTROL_HANDLER = 'An error occured while processing VALID control event handler'
        ,   MESSAGE_ERROR_OCCURED_WHILE_PROCESSING_INVALID_CONTROL_HANDLER = 'An error occured while processing INVALID control event handler'
        ,   MESSAGE_ERROR_OCCURED_WHILE_PROCESSING_BEFORE_CONTROL_HANDLER = 'An error occured while processing BEFORE control event handler'
        ,   MESSAGE_ERROR_OCCURED_WHILE_PROCESSING_AFTER_CONTROL_HANDLER = 'An error occured while processing AFTER control event handler'
        ,   MESSAGE_ERROR_OCCURED_WHILE_PROCESSING_VALID_FORM_HANDLER = 'An error occured while processing VALID form event handler'
        ,   MESSAGE_ERROR_OCCURED_WHILE_PROCESSING_INVALID_FORM_HANDLER = 'An error occured while processing INVALID form event handler'
        ,   MESSAGE_ERROR_OCCURED_WHILE_PROCESSING_BEFORE_FORM_HANDLER = 'An error occured while processing BEFORE form event handler'
        ,   MESSAGE_ERROR_OCCURED_WHILE_PROCESSING_AFTER_FORM_HANDLER = 'An error occured while processing AFTER form event handler'
        ,   MESSAGE_TRYING_TO_PARSE_NOT_DOM_ELEMENT = 'Trying to parse for validator-tags not DOM element'
        ,   MESSAGE_TARGET_ATTRIBUTE_IS_REQUIRED_TAG_IGNORED = 'Target attribute for validator-tag is required, tag ignored'
        ,   MESSAGE_INTERNAL_ERROR_NO_VALIDATOR_WHILE_PARSING = 'Internal error: cannot find validator for tag'
        ,   MESSAGE_NO_CORRESPONDING_METHOD_IN_API_FOR_ATTRIBUTE = 'Wrong attribute: no corresponding method in Validatod-API'
        ,   MESSAGE_WRONG_ATTRIBUTE_TAG_IGNORED = 'Wrong attribute, validator-tag ignored'
        ,   MESSAGE_UNCLOSED_ATTRIBUTE_VALUE_TAG_IGNORED = 'Unclosed attribute, validator-tag ignored'
        ,   MESSAGE_UNCLOSED_TAG_IGNORED = 'Validator-tag is unclosed and ignored'
        ,   MESSAGE_UNCLOSED_COMMENT_IGNORED = 'Unclosed comment ignored' 
        ,   MESSAGE_HANDLER_MUST_BE_AN_FUNCTION_OR_JAVASCRIPT_EXPRESSION = 'Event handler must be an function or javascript expression'
        ,   MESSAGE_SUCH_VALIDATOR_ALREADY_HAS_BEEN_ATTACHED = 'Such validator function already has been attached'
        ;
    // end of private const

    // private properties
    var
            f_settings = { // most common settings
                valid_class: 'valid'
                ,invalid_class: 'invalid'
            } // end of settings
        ,   f_parser = function() { // parser for DOM elements
                // private methods
                var
                    extract = function(content, open_token, close_token, need_trim) {
                        // trim if need
                        content = need_trim ? trim(content) : content;
                        // create result object
                        var result = { rest: content, 'content': '', before: '', found: false, closed: true };
                        // search for open token
                        var open_index = result.rest.indexOf(open_token);
                        result.found = -1 < open_index;
                        // proceed if found
                        if (result.found) {
                            result.before = result.rest.substr(0, open_index);
                            result.rest = result.rest.substr(open_index + open_token.length);
                            // find closure
                            var close_index = result.rest.indexOf(close_token);
                            result.closed = -1 < close_index;
                            // if closed then proceed
                            if (result.closed) {
                                result.content = result.rest.substr(0, close_index);
                                result.rest = result.rest.substr(close_index + close_token.length);
                            } // end of closed
                        } // end of found
                        // return result object
                        return result;
                    } // end of extract

                    // trim leading spaces
                    ,trim = function(content) {
                        return content.replace(/^\s*/, ''); //.replace(/\s*$/, "");
                    } // end of trim

                    // apply attributes to tag
                    ,apply = function(attributes) {
                        // no attrs - ignore tag
                        if (!attributes) { return; }
                        // "target" is required
                        if (!attributes['target']) {
                            KIR.log(MESSAGE_TARGET_ATTRIBUTE_IS_REQUIRED_TAG_IGNORED);
                            return;
                        }
                        // get validator for element
                        var validator = KIR.validator(attributes['target']);
                        // internal error: target not found
                        if (!validator) {
                            KIR.log(MESSAGE_INTERNAL_ERROR_NO_VALIDATOR_WHILE_PARSING);
                            return;
                        }
                        // for all attrs
                        for (var name in attributes) {
                            // skip null- and target- attrs
                            if (name && 'target' != name) {
                                // apply value
                                if (validator[name] && 'function' == typeof validator[name]) {  
                                    validator[name].apply(
                                        validator, // validator object
                                        KIR.utils.array(KIR.core.evaluate(attributes[name])) // converted args
                                    );
                                } else {
                                    // or log if no corresponding method in validator API
                                    KIR.log(MESSAGE_NO_CORRESPONDING_METHOD_IN_API_FOR_ATTRIBUTE);
                                    return;
                                }
                            }
                        } // end of cycling on attrs
                    } // end of apply

                    // parses tag for attributes
                    ,attrs = function(content) {
                        // returned attributes
                        var attrs = {};
                        // skip blanks, get the content and parse it for attributes
                        var result = extract(content, '="', '"', true);
                        // ignore tag if attribute name not found
                        if (!result.found) {
                            KIR.log(MESSAGE_WRONG_ATTRIBUTE_TAG_IGNORED);
                            return null;
                        }
                        // while no errors
                        while (result.found && result.closed) {
                            // add record to attrs
                            attrs[result.before] = result.content;
                            // go to next attribute
                            result = extract(result.rest, '="', '"', true);
                        } // end of searching for tags
                        // ignore tag if attibute value is unclosed
                        if (result.found && !result.closed) {
                            KIR.log(MESSAGE_UNCLOSED_ATTRIBUTE_VALUE_TAG_IGNORED);
                            return null;
                        }
                        // all is ok
                        return attrs;
                    } // end of parsing tag for attributes

                    // parses comment's content for tags
                    ,tags = function(content) {
                        // get the content and parse it for tags
                        var result = extract(content, '<validator', '/>');
                        // while no errors
                        while (result.found && result.closed) {
                            // parse tag attrs and apply them
                            apply(attrs(result.content));
                            // go to next tag
                            result = extract(result.rest, '<validator', '/>');
                        } // end of searching for tags
                        // log if tag is incorrect
                        if (result.found && !result.closed) { KIR.log(MESSAGE_UNCLOSED_TAG_IGNORED); }
                    } // end of tags
                    ;
                // end of private methods


                // public properties and methods
                return {
                    // parse dom element's content for validator tags
                    // if tag contains an error then tag is ignored
                    parse: function(html_dom_element) {
                        // check for DOM
                        if (!KIR.DOM.is_DOM(html_dom_element)) { throw(MESSAGE_TRYING_TO_PARSE_NOT_DOM_ELEMENT); }
                        // get the content and parse it for comments
                        var result = extract(html_dom_element.innerHTML, '<!--', '-->');
                        // while no errors
                        while (result.found && result.closed) {
                            // parse tag
                            tags(result.content);
                            // go to next comment
                            result = extract(result.rest, '<!--', '-->');
                        } // end of searching for comments
                        // log if tag is incorrect
                        if (result.found && !result.closed) { KIR.log(MESSAGE_UNCLOSED_COMMENT_IGNORED); }
                    } // end of parse
                }; // end of _self definition
            }() // end of parser definition
        ;
    // end of private properties

    // private methods
    var
        // checks whether element is html form
        //  - html_dom_element: HTML DOM element
        is_form = function(html_dom_element) {
            return KIR.DOM.is_DOM(html_dom_element) && html_dom_element.tagName.toUpperCase() == 'FORM';
        } // end of is_form
        
        // finds html form for html DOM control element,
        //  - returns html DOM form element if found
        //  - returns null if no one found
        //  - html_dom_element: HTML DOM element
        ,find_form = function(html_dom_element) {
            if (!KIR.DOM.is_DOM(html_dom_element)) { throw(MESSAGE_TRYING_TO_FIND_FORM_FOR_NOT_HTML_DOM_ELEMENT); }
            while (document != html_dom_element && !is_form(html_dom_element)) {
                html_dom_element = html_dom_element.parentNode;
            }
            return is_form(html_dom_element) ? html_dom_element : null;
        } // end of find_form
        
        // checks whether element is DOM control element 
        // supporting KIR control interface
        //  - html_dom_element: HTML DOM element
        ,is_control = function(html_dom_element) {
            // check for null too
            return KIR.DOM.is_DOM(html_dom_element) && KIR.core.namespace(html_dom_element) && KIR.core.namespace(html_dom_element).control;
        } // end of is_control
        
        // create validation interface for object
        // element may be:
        //  - HTML DOM element
        //  - NULL 
        // exception is thrown in other cases
        ,create = function(element) {
            // private properties
            //var
            //    ;
            // end of private properties
            
            // private methods
            //var
            //    ;
            // end of private methods
            
            // object itself with common methods
            var _self = {
                // required validator
                //  - control: control to validate with interface defined by KIR.controls
                required_validator: function(control) {
                    // todo: check for KIR interface
                    return null != control.get_value() && '' != control.get_value();
                } // end of required_validator
                
                // minlength validator
                //  - control: control to validate with interface defined by KIR.controls
                //  - params.minlength: minimal length
                // todo: throw exception if params is not a number
                ,minlength_validator: function(control, params) {
                    // todo: check for KIR interface
                    return null != control.get_value() && params.minlength <= control.get_value().length;
                } // end of minlength_validator
                
                // maxlength validator
                //  - control: control to validate with interface defined by KIR.controls
                //  - params.maxlength: maximal length
                // todo: throw exception if params is not a number
                ,maxlength_validator: function(control, params) {
                    // todo: check for KIR interface
                    return null != control.get_value() && params.maxlength >= control.get_value().length;
                } // end of maxlength_validator
                
                // regex validator
                //  - control: control to validate with interface defined by KIR.controls
                //  - params.regex: regular expression for match
                // todo: throw exception if params is not a number
                ,regex_validator: function(control, params) {
                    // todo: check for KIR interface
                    return null != control.get_value() && params.regex.test(control.get_value());
                } // end of regex_validator
                
                // range validator
                //  - control: control to validate with interface defined by KIR.controls
                //  - params.min: min value of range
                //  - params.max: max value of range
                // todo: throw exception if params is not a number
                ,range_validator: function(control, params) {
                    // todo: check for KIR interface
                    // strange bug: if no temporary variable then function returns "undefined" value
                    var result = 
                        null != control.get_value() 
                        && params.min <= parseInt(control.get_value())
                        && params.max >= parseInt(control.get_value());
                    return result;
                } // end of range_validator
                
                // numeric validator
                //  - control: control to validate with interface defined by KIR.controls
                // todo: throw exception if params is not a number
                ,numeric_validator: function(control) {
                    // todo: check for KIR interface
                    // strange bug: if no temporary variable then function returns "undefined" value
                    // todo: don't create new regexp any time
                    var regex = new RegExp('^[\-]?(0|[1-9][0-9]*)(\.[0-9]+([\+\-][eE][1-9][0-9]*)*)?$');
                    var result = 
                        null != control.get_value() 
                        ///^[\-]?(0|[1-9][0-9]*)(\.[0-9]+([\+\-][eE][1-9][0-9]*)*)?$.test(html_dom_element.get_value());
                        && regex.test(control.get_value());
                    return result;
                } // end of numeric_validator
                
                // equals validator
                //  - control: control to validate with interface defined by KIR.controls
                //  - params.source: id/name/dom/jquery of source element value
                ,equals_validator: function(control, params) {
                    // todo: check for KIR interface, source too
                    // convert source to dom
                    var source = params.source;
                    // strange bug: if no temporary variable then function returns "undefined" value
                    var result = 
                        null != control.get_value()
                        && null != source.get_value()
                        && control.get_value() == source.get_value();
                    return result;
                } // end of equals_validator
                
                // set settings for all system
                ,settings: function(settings) {
                    // replace all params
                    for (var index in settings) {
                        f_settings[index] = settings[index];
                    }
                    // jQuery-style return
                    return _self;
                } // end of settings
            };
            
            // validator()-specific methods
            if (!is_form(element) && !is_control(element)) {
                // takes single element of array of id/name/DOM/jQuery
                // and parses it for validation tags
                _self.parse = function(element) {
                    // convert all into array
                    element = KIR.utils.array(
                        KIR.core.convert(element)
                    );
                    // for elements - call inner parser
                    for (var i=0; i<element.length; i++) {
                        f_parser.parse(element[i]);
                    } // end of parsing
                }; // end of parse
            } // end of validator()-specific methods
            
            // attach form methods
            if (is_form(element)) {
                // private form properties 
                var
                        f_form_hash = KIR.DOM.hash(element) // get hash code
                    ,   f_form_html_element = element      // HTML element
                    ,   f_form_place = null // place error messages here
                    ,   f_form_only_first = false // show only first input error
                    ,   f_form_stop_on_first = false // stop after first fail
                    ,   f_form_a4jsubmitters = [] // array of submitters
                    ,   f_form_a4jsubmit = true // validate on submit
                    ,   f_form_a4jprevent = true // prevent submitting invalid form
                    ,   f_form_before = null           // script before validation
                    ,   f_form_after = null            // script after validation
                    ,   f_form_valid = null          // script if element is valid
                    ,   f_form_invalid = null        // script if element is invalid
                    ;
                // end of private form properties
                
                // getter for hash code
                _self.hash = function() {
                    return f_form_hash;
                }; // end of hash

                // getter for form element
                _self.element = function() {
                    return f_form_html_element;
                }; // end of element()

                // validate form
                _self.validate = function() {
                    if (f_form_before) { try { f_form_before(_self); } catch(exception) { KIR.log(MESSAGE_ERROR_OCCURED_WHILE_PROCESSING_BEFORE_FORM_HANDLER); } }
                    // for all controls inside that have validators
                    var controls = KIR.core.filter(KIR.controls.find(f_form_html_element), 'validator');
                    for (var i=0; i<controls.length; i++) {
                        var control = KIR.core.namespace(controls[i]).validator;
                        // process validation on control
                        // if fails, check stop_on_first and break process of validation
                        if (!control.validate() && f_form_stop_on_first) { break; }
                    } // end of controls cycle
                    // update messages because if stop_on_first is on they may be invalid after validation
                    _self.messages();
                    // todo: messenger
                    if (_self.is_valid()) {
                        if (f_form_valid) { try { f_form_valid(_self); } catch(exception) { KIR.log(MESSAGE_ERROR_OCCURED_WHILE_PROCESSING_VALID_FORM_HANDLER); } }
                    } else {
                        if (f_form_invalid) { try { f_form_invalid(_self); } catch(exception) { KIR.log(MESSAGE_ERROR_OCCURED_WHILE_PROCESSING_INVALID_FORM_HANDLER); } }
                    }
                    if (f_form_after) { try { f_form_after(_self); } catch(exception) { KIR.log(MESSAGE_ERROR_OCCURED_WHILE_PROCESSING_AFTER_FORM_HANDLER); } }
                    // return result of validation
                    return _self.is_valid(); 
                }; // end of validate form
                
                // just check the validators, ignore before/after scripts,
                // event handlers, messages and internal validators' state
                _self.instant = function() {
                    var result = true;
                    // for all controls inside that have validators
                    var controls = KIR.core.filter(KIR.controls.find(f_form_html_element), 'validator');
                    for (var i=0; i<controls.length; i++) {
                        // process validation on control
                        var control = KIR.core.namespace(controls[i]).validator;
                        result = result && control.instant();
                        // if fails, check stop_on_first and break process of validation
                        if (result && f_form_stop_on_first) { break; }
                    } // end of controls cycle
                    return result;
                }; // end of instant
                
                // validity flag
                _self.is_valid = function() {
                    var result = true;
                    // for all controls inside that have validators
                    var controls = KIR.core.filter(KIR.controls.find(f_form_html_element), 'validator');
                    for (var i=0; i<controls.length; i++) {
                        result = result && KIR.core.namespace(controls[i]).validator.is_valid();
                    }
                    return result;
                }; // end of is_valid
                
                // place error messages
                // removes all messages of this form and calls for controls' methods
                //  - place: container for messages
                _self.messages = function(place) {
                    // if no param - use default place
                    if (!place) place = f_form_place; else place = KIR.core.convert(place);
                    // if no place for messages specified - ignore invocation
                    if (!place) { return _self; } // jQuery-style return
                    // remove all messages from this form
                    jQuery(place).find('span[id*=' + f_form_hash + ']').remove();
                    // todo: controls remove messages too and it's duplicating
                    // for all controls inside that have validators
                    var controls = KIR.core.filter(KIR.controls.find(f_form_html_element), 'validator');
                    for (var i=0; i<controls.length; i++) {
                        KIR.core.namespace(controls[i]).validator.messages(place);
                        // if only one input should be shown and messages were added - exit
                        if (f_form_only_first && !KIR.core.namespace(controls[i]).validator.is_valid()) { break; }
                    }
                    // jQuery-style return
                    return _self;
                }; // end of update_messages
                
                // set container for error messages
                _self.place = function(place) {
                    f_form_place = KIR.core.convert(place);
                    // jQuery-style return
                    return _self;
                }; // end of place
                
                // set "only first control message" flag
                // impacts only on entire control selection for messages placing
                // individual controls use their own settings of this parameter
                _self.only_first = function(value) {
                    f_form_only_first = 'boolean' == typeof value ? value : true;
                    // jQuery-style return
                    return _self;
                }; // end of only_first
                
                // set "stop on first" flag
                _self.stop_on_first = function(value) {
                    f_form_stop_on_first = 'boolean' == typeof value ? value : true;
                    // jQuery-style return
                    return _self;
                }; // end of stop_on_first

                // set script BEFORE the validation
                _self.before = function(handler) {
                    // if handler is null then f_form_before becomes null after conversion in KIR.utils.func
                    f_form_before = KIR.core.evaluate(handler);
                    // check for right value
                    if (null != f_form_before && 'function' != typeof f_form_before) { throw(MESSAGE_HANDLER_MUST_BE_AN_FUNCTION_OR_JAVASCRIPT_EXPRESSION); }
                    // jQuery-style return
                    return _self;
                }; // end of before
                
                // set script AFTER the validation
                _self.after = function(handler) {
                    // if handler is null then f_form_after becomes null after conversion in KIR.utils.func
                    f_form_after = KIR.core.evaluate(handler);
                    // check for right value
                    if (null != f_form_after && 'function' != typeof f_form_after) { throw(MESSAGE_HANDLER_MUST_BE_AN_FUNCTION_OR_JAVASCRIPT_EXPRESSION); }
                    // jQuery-style return
                    return _self;
                }; // end of after
                
                // set script on VALID
                _self.valid = function(handler) {
                    // if handler is null then f_form_valid becomes null after conversion in KIR.utils.func
                    f_form_valid = KIR.core.evaluate(handler);
                    // check for right value
                    if (null != f_form_valid && 'function' != typeof f_form_valid) { throw(MESSAGE_HANDLER_MUST_BE_AN_FUNCTION_OR_JAVASCRIPT_EXPRESSION); }
                    // jQuery-style return
                    return _self;
                }; // end of valid
                
                // set script on INVALID
                _self.invalid = function(handler) {
                    // if handler is null then f_form_invalid becomes null after conversion in KIR.utils.func
                    f_form_invalid = KIR.core.evaluate(handler);
                    // check for right value
                    if (null != f_form_invalid && 'function' != typeof f_form_invalid) { throw(MESSAGE_HANDLER_MUST_BE_AN_FUNCTION_OR_JAVASCRIPT_EXPRESSION); }
                    // jQuery-style return
                    return _self;
                }; // end of invalid
                
                // setter for "prevent if invalid"
                _self.a4jprevent = function(value) {
                    f_form_a4jprevent = 'boolean' == typeof value ? value : true;
                    // jQuery-style return
                    return _self;
                }; // end of a4jsubmit

                // setter for submitters
                _self.a4jsubmitters = function(value) {
                    // convert to DOM array
                    f_form_a4jsubmitters = KIR.utils.array(
                        KIR.core.convert(value)
                    );
                    // jQuery-style return
                    return _self;
                }; // end of submitters

                
                // setter for "validate on submit"
                _self.a4jsubmit = function(value) {
                    f_form_a4jsubmit = 'boolean' == typeof value ? value : true;
                    // jQuery-style return
                    return _self;
                }; // end of a4jsubmit

                // check submission of form
                //  - event that caused submission must be specified
                //  - validate if needed
                //  - returns false if need to prevent and form is not valid
                _self.check_submit = function(e) {
                    // get real event
                    e = KIR.DOM.get_real_event(e);
                    // get event target element
                    var target = KIR.DOM.get_real_event_target(e);
                    // if no submitters then check that target is input[submit]
                    var found = f_form_a4jsubmitters.length == 0 && target && 'submit' == target.type;
                    // check submitters // todo: simplify - hash array
                    for (var i=0; i<f_form_a4jsubmitters.length; i++) {
                        if (f_form_a4jsubmitters[i] == target) {
                            found = true;
                        }
                    }
                    // if no submitter activated then it's not event to process
                    if (!found) { return true; }
                    // validate if need
                    if (f_form_a4jsubmit) { _self.validate(); }
                    // prevent if need
                    return !f_form_a4jprevent || _self.is_valid();
                }; // end of check_submit
                
                // initialization: put our a4jsubmit handler to allow validate form
                // on submit an prevent it
                KIR.events.a4jsubmit(f_form_html_element, _self.check_submit);
            } // end of form methods
            
            // is this a KIR-interface control?
            if (is_control(element)) { 
                // private properties
                var
                        f_control_hash = KIR.DOM.hash(element) // ID
                    ,   f_control = KIR.core.namespace(element).control      // KIR.control namespace
                    ,   f_control_validators = new KIR.utils.Stack()      // array of validators
                    ,   f_control_force = false                     // force form validation
                    ,   f_control_form = KIR.core.namespace(find_form(element)).validator    // parent form todo: simplify
                    ,   f_control_before = null
                    ,   f_control_after = null
                    ,   f_control_valid = null            // script on valid
                    ,   f_control_invalid = null          // script on invalid
                    ,   f_control_stop_on_first = true              // stop on first fail
                    ,   f_control_only_first = true                 // show only first message
                    ,   f_control_place = null                      // place for validation messages
                    ;
                // end of private properties
                
                // check that form is found
                // todo: is it possible?
                if (null == f_control_form) { throw(MESSAGE_FORM_VALIDATION_INTERFACE_FOR_CONTROL_NOT_FOUND_IN_HASH); }
                
                // getter for hash code
                _self.hash = function() {
                    return f_control_hash;
                }; // end of hash

                // private methods
                var
                    // invoke an validation
                    invoke = function() {
                        if (f_control_force) {
                            f_control_form.validate();
                        } else {
                            _self.validate();
                        }
                    } // end of invoke
                    ;
                // end of private methods
                
                // show messages
                // removes all messages of this control and places new
                //  - place: container for messages
                _self.messages = function(place) {
                    // if no param - use default place
                    if (!place) place = f_control_place; else place = KIR.core.convert(place);
                    // if no place for messages specified - ignore invocation
                    if (!place) { return _self; } // jQuery-style return
                    // remove all error messages of this control
                    jQuery(place).find('span[id*=' + f_control_hash + ']').remove();
                    for (var i=0; i<f_control_validators.size(); i++) {
                        if (!f_control_validators.get(i).is_valid()) {
                            // add message and set id for container by combining this form id
                            // and element id and validator index
                            // messages are placed on top of container
                            jQuery(place).append('<span id="' + f_control_form.hash() + '-' + f_control_hash + '-' + i + '" class="validation-messages">'
                                + f_control_validators.get(i).get_message()
                            + '</span>');
                            // exit if need to show only one message at moment
                            if (f_control_only_first) { break; }
                        }
                    }
                    // jQuery-style return
                    return _self;
                }; // end of update_messages
                
                // validity flag
                _self.is_valid = function() {
                    var result = true;
                    for (var i=0; i<f_control_validators.size(); i++) {
                        result = result && f_control_validators.get(i).is_valid();
                    }
                    return result;
                }; // end of is_valid;
            
                // validate control
                _self.validate = function() {
                    if (f_control_before) { try { f_control_before(_self); } catch(exception) { KIR.log(MESSAGE_ERROR_OCCURED_WHILE_PROCESSING_BEFORE_CONTROL_HANDLER); } }
                    // for all validators
                    for (var i=0; i<f_control_validators.size(); i++) {
                        // process validation
                        f_control_validators.get(i).invoke();
                        // if validation fails and stop_on_first is on then we must stop the process
                        if (!f_control_validators.get(i).is_valid() && f_control_stop_on_first) { break; }
                    } // end of validators cycle
                    // update messages of control
                    _self.messages(); 
                    // update messages of form
                    f_control_form.messages(); // todo: optimize; too many invokations
                    // todo: messenger
                    // run event handlers, set style class, and invoke control validation handlers
                    // note: if validity changes due to external errors
                    // (like disable/enable but some others) the class will remain
                    // the same, that is error
                    if (_self.is_valid()) {
                        jQuery(f_control.get_html()).removeClass(f_settings.invalid_class);
                        jQuery(f_control.get_html()).addClass(f_settings.valid_class);
                        f_control.valid();
                        if (f_control_valid) { try { f_control_valid(_self); } catch(exception) { KIR.log(MESSAGE_ERROR_OCCURED_WHILE_PROCESSING_VALID_CONTROL_HANDLER); } }
                    } else {
                        jQuery(f_control.get_html()).addClass(f_settings.invalid_class);
                        jQuery(f_control.get_html()).removeClass(f_settings.valid_class);
                        f_control.invalid();
                        if (f_control_invalid) { try { f_control_invalid(_self); } catch(exception) { KIR.log(MESSAGE_ERROR_OCCURED_WHILE_PROCESSING_INVALID_CONTROL_HANDLER); } }
                    }
                    if (f_control_after) { try { f_control_after(_self); } catch(exception) { KIR.log(MESSAGE_ERROR_OCCURED_WHILE_PROCESSING_AFTER_CONTROL_HANDLER); } }
                    // return result of validation
                    return _self.is_valid(); 
                }; // end of validate control

                // just check the validators, ignore before/after scripts,
                // event handlers, messages and internal validators' state
                _self.instant = function() {
                    var result = true;
                    // for all validators
                    for (var i=0; i<f_control_validators.size(); i++) {
                        // process instant validation
                        result = result && f_control_validators.get(i).instant();
                        // if validation fails and stop_on_first is on then we must stop the process
                        if (!result && f_control_stop_on_first) { break; }
                    } // end of validators cycle
                    return result;
                }; // end of instant
                
                // detach validator
                _self.detach = function(validator) {
                    for (var i=0; i<f_control_validators.size(); i++) {
                        if (validator == f_control_validators.get(i).get_validator()) {
                            f_control_validators.remove(f_control_validators.get(i));    
                            break;
                        }
                    }
                    // make visual effects
                    f_control.effects();
                }; // end of detach
                
                // attach validator
                // allows to attach only one function of each type
                _self.attach = function(validator, params, message) {
                    // check that such a function is not attached
                    for (var i=0; i<f_control_validators.size(); i++) {
                        if (validator == f_control_validators.get(i).get_validator()) {
                            KIR.log(MESSAGE_SUCH_VALIDATOR_ALREADY_HAS_BEEN_ATTACHED);
                            return;
                        }
                    }
                    // create object with private fields
                    // maybe it's too complicated but... I believe it will help us in future
                    f_control_validators.push(function() {
                        // private fields
                        var
                                f_valid = true
                            ,   f_validator = validator
                                // I don't know why but I prefer to store HTML DOM element in separate field but not inside parent class
                            ,   f_KIR_control = f_control
                            ,   f_params = params
                            ,   f_message = message;
                            ;
                        // end of private fields
                            
                        // definition of object
                        return {
                            // get the validator function
                            get_validator: function() {
                                return f_validator;
                            } // end of get_validator
                            
                            // just run validator
                            ,instant: function() {
                                return f_validator(f_KIR_control, f_params);
                            } // end of instant
                            
                            // process validation and set flag
                            ,invoke: function() {
                                f_valid = this.instant();
                            } // end of invoke
                            
                            // if input is disabled then it's valid whenether its value
                            ,is_valid: function() {
                                return f_KIR_control.is_disabled() ? true : f_valid;
                            }  // end of is_valid
                            
                            // get invalid validation message
                            ,get_message: function() {
                                return f_message;
                            } // end of get_message
                        }; // end of definition of object
                    }()); // end of creating of validator and adding it in array
                    // make visual effects
                    f_control.effects();
                    // jQuery-style return
                    return _self;
                }; // end of attach
                
                // get all error messages for this control
                _self.get_messages = function() {
                    var result = [];
                    for (var i=0; i<f_control_validators.size(); i++) {
                        if (!f_control_validators.get(i).is_valid()) { result.push(f_control_validators.get(i).get_message()); }
                    }
                    return result;
                }; // end of get_messages
                
                // mark as custom
                _self.custom = function(validator, message, params) {
                    // detach
                    if (null != message && 'boolean' == typeof message && !message) { 
                        _self.detach(validator); return _self;
                    }
                    // attach
                    _self.attach(validator, params, message);
                    // jQuery-style return
                    return _self;
                }; // end of custom
                
                // mark as required
                //  - message: custom validation message
                _self.required = function(message) {
                    // detach
                    if (null != message && 'boolean' == typeof message && !message) { 
                        _self.detach(_self.required_validator); 
                        return _self;
                    }
                    // attach
                    _self.attach(_self.required_validator, {}, message);
                    // jQuery-style return
                    return _self;
                }; // end of required
                
                // getter for required
                _self.is_required = function() {
                    for (var i=0; i<f_control_validators.size(); i++) {
                        if (_self.required_validator == f_control_validators.get(i).get_validator()) { return true; }
                    }
                    return false;
                }; // end of is_required
                
                // mark as minlength
                _self.minlength = function(message, minlength) {
                    // detach
                    if (null != message && 'boolean' == typeof message && !message) { 
                        _self.detach(_self.minlength_validator); 
                        return _self;
                    }
                    _self.attach(_self.minlength_validator, {'minlength' : minlength}, message);
                    // jQuery-style return
                    return _self;
                }; // end of minlength
                
                // mark as maxlength
                _self.maxlength = function(message, maxlength) {
                    // detach
                    if (null != message && 'boolean' == typeof message && !message) { 
                        _self.detach(_self.maxlength_validator); 
                        return _self;
                    }
                    // attach
                    _self.attach(_self.maxlength_validator, {'maxlength' : maxlength}, message);
                    // jQuery-style return
                    return _self;
                }; // end of maxlength
                
                // mark as regex
                _self.regex = function(message, regex) {
                    // detach
                    if (null != message && 'boolean' == typeof message && !message) { 
                        _self.detach(_self.regex_validator); 
                        return _self;
                    }
                    // create regex
                    regex = new RegExp(regex); 
                    // and attach
                    _self.attach(_self.regex_validator, {'regex' : regex}, message);
                    // jQuery-style return
                    return _self;
                }; // end of regex

                // mark as range
                _self.range = function(message, min, max) {
                    // detach
                    if (null != message && 'boolean' == typeof message && !message) { 
                        _self.detach(_self.range_validator); 
                        return _self;
                    }
                    // attach
                    _self.attach(_self.range_validator, {'min' : min, 'max' : max}, message);
                    // jQuery-style return
                    return _self;
                }; // end of range

                // mark as equals
                _self.equals = function(message, source) {
                    // detach
                    if (null != message && 'boolean' == typeof message && !message) { 
                        _self.detach(_self.equals_validator); 
                        return _self;
                    }
                    // attach
                    // todo: check for KIR interface
                    _self.attach(_self.equals_validator, {'source' : KIR.core.namespace(source).control}, message);
                    // jQuery-style return
                    return _self;
                }; // end of equals
                
                // mark as numeric
                _self.numeric = function(message) {
                    // detach
                    if (null != message && 'boolean' == typeof message && !message) { 
                        _self.detach(_self.numeric_validator); 
                        return _self;
                    }
                    // attach
                    _self.attach(_self.numeric_validator, {}, message);
                    // jQuery-style return
                    return _self;
                }; // end of numeric

                // attach on typing
                _self.typing = function(attach) {
                    KIR.events.typing(f_control.get_html(), invoke, attach);
                    // jQuery-style return
                    return _self;
                }; // end of typing
                
                // attach on change
                _self.change = function(attach) {
                    KIR.events.change(f_control.get_html(), invoke, attach);
                    // jQuery-style return
                    return _self;
                }; // end of change
                
                // attach on blur
                _self.blur = function(attach) {
                    KIR.events.blur(f_control.get_html(), invoke, attach);
                    // jQuery-style return
                    return _self;
                }; // end of blur
                
                // set place for messages
                _self.place = function(place) {
                    f_control_place = KIR.core.convert(place);
                    // jQuery-style return
                    return _self;
                }; // end of place
                
                // set "only first message" flag
                _self.only_first = function(value) {
                    f_control_only_first = 'boolean' == typeof value ? value : true;
                    // jQuery-style return
                    return _self;
                }; // end of only_first
                
                // set "stop on first" flag
                _self.stop_on_first = function(value) {
                    f_control_stop_on_first = 'boolean' == typeof value ? value : true;
                    // jQuery-style return
                    return _self;
                }; // end of stop_on_first
                
                // get the parent form
                _self.form = function() {
                    return f_control_form;
                }; // end of form
                
                // force validation of the entire form
                _self.force = function(value) {
                    f_control_force = 'boolean' == typeof value ? value : true;
                    // jQuery-style return
                    return _self;
                }; // end of force
                
                // set script BEFORE the validation
                _self.before = function(handler) {
                    // if handler is null then f_control_before becomes null after conversion in KIR.utils.func
                    f_control_before = KIR.core.evaluate(handler);
                    // check for right value
                    if (null != f_control_before && 'function' != typeof f_control_before) { throw(MESSAGE_HANDLER_MUST_BE_AN_FUNCTION_OR_JAVASCRIPT_EXPRESSION); }
                    // jQuery-style return
                    return _self;
                }; // end of before
                
                // set script AFTER the validation
                _self.after = function(handler) {
                    // if handler is null then f_control_after becomes null after conversion in KIR.utils.func
                    f_control_after = KIR.core.evaluate(handler);
                    // check for right value
                    if (null != f_control_after && 'function' != typeof f_control_after) { throw(MESSAGE_HANDLER_MUST_BE_AN_FUNCTION_OR_JAVASCRIPT_EXPRESSION); }
                    // jQuery-style return
                    return _self;
                }; // end of after
                
                // set script on VALID
                _self.valid = function(handler) {
                    // if handler is null then f_control_valid becomes null after conversion in KIR.utils.func
                    f_control_valid = KIR.core.evaluate(handler);
                    // check for right value
                    if (null != f_control_valid && 'function' != typeof f_control_valid) { throw(MESSAGE_HANDLER_MUST_BE_AN_FUNCTION_OR_JAVASCRIPT_EXPRESSION); }
                    // jQuery-style return
                    return _self;
                }; // end of valid
                
                // set script on INVALID
                _self.invalid = function(handler) {
                    // if handler is null then f_control_invalid becomes null after conversion in KIR.utils.func
                    f_control_invalid = KIR.core.evaluate(handler);
                    // check for right value
                    if (null != f_control_invalid && 'function' != typeof f_control_invalid) { throw(MESSAGE_HANDLER_MUST_BE_AN_FUNCTION_OR_JAVASCRIPT_EXPRESSION); }
                    // jQuery-style return
                    return _self;
                }; // end of invalid
            } // end of control methods
            
            return _self;
        } // end of create
            
        // check whether the passed html DOM element is correct
        //  - html_dom_element: HTML DOM element
        // return false if not
        ,is_valid = function(html_dom_element) {
            // HTML DOM checked inside is_form and is_control
            return is_form(html_dom_element) || is_control(html_dom_element);
        } // end of is_valid
        
        ;
    // end of private methods

    // this is the CORE function of validation
    // it gets:
    //  - empty param - and after that returns object with interface allowing you to make some settings
    //  - form id/form element - and after thate returns object with basic form validation interface
    //  - control id/control name/control element - and after that returns object with basic control validation interface
    // note: if incorrect param is passed, the function processes as for empty param
    // todo: work with jQuery-arrays
    return function(element) {
        // convert id/element/jquery to HTML DOM element
        element = KIR.core.convert(element);
        // if no param is passed we just create and return object
        if (!element) { return create(); }
        // in another case we must check validity
        if (!is_valid(element)) { KIR.log(MESSAGE_INVALID_ELEMENT); return null; }
        // if it's control and no form validation interface exists for parent form - we create it
        if (!is_form(element) && !KIR.core.namespace(find_form(element)).validator) { KIR.core.namespace(find_form(element)).validator = create(find_form(element)); }
        // if no form/control object exists we create it
        if (!KIR.core.namespace(element).validator) { KIR.core.namespace(element).validator = create(element); }
        // and return
        return KIR.core.namespace(element).validator;
    }; // end of CORE validation function
}(); // this function's call create object
// end of KIR.validator object definition

// *** TESTS ***

// initialization
// parse AJAX response for validators
KIR.events.a4jcomplete(function(e, element, updated) {
    for (var i=0; i<updated.length; i++) {
        KIR.validator().parse(updated[i]);
    }
}); // end of defining event handler for parsing of responses

}); // end of jQuery(document).ready()
