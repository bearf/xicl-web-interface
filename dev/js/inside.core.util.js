// --- inside.core.util ---
// version of oct 20, 2011

(function($) {

    // --- pre-phase ---

    if (!window.inside) { throw('global object [inside] not found'); }
    if (!window.inside.core) { throw('global object [inside.core] not found'); }
    
    if  (window.inside.core.util) { return false; }

    // --- new namespace ---
    window.inside.core.util = {

            // convert jquery-presentation of html dom object
            object: function(idObject, parent_selector) {
                // no idObject? go out!
                if (!idObject) { return null; }

                // find jQuery-container
                if (1 == idObject.nodeType) {
                    // if idObject is DOM node
                    var $object = undefined != parent_selector
                        ? $(idObject).closest(parent_selector)
                        : $(idObject);
                } else if (idObject.animate) {
                    // if the idObject is a jQuery
                    var $object = undefined != parent_selector
                        ? idObject.closest(parent_selector)
                        : idObject;
                } else {
                    // if the idObject is a string
                    var $object = $('[id$="' + idObject + '"]');
                    if (!$object.size()) {
                        $object = $('[name$="' + idObject + '"]');
                    }
                    if (parent_selector) { $object = $object.closest(parent_selector); }
                }

                // check whether jQuery has found container
                return $object.size() ? $object : null;
            } // end of object

            // check whether idObject positioned relatively, absolutely or fixed
        ,   is_positioned: function(idObject) {
                var
                        $object     = this.object(idObject)
                    ;
                if ($object.is('html')) { return true; }
                var
                        position    = $object.css('position')
                    ;
                return  'fixed' == position
                    ||  'relative' == position
                    ||  'absolute' == position
                    ;
            } // is_positioned

        ,   getCaretPos: function(idObject) {
                var
                        object      = this.object(idObject).get(0)
                    ,   caret_pos   = 0
                    ;
                object.focus();

                if (object.selectionStart) {
                    // Gecko
                    caret_pos = object.selectionStart;
                } else if (document.selection) {
                    // IE
                    var
                            selection   = document.selection.createRange()
                        ,   clone       = selection.duplicate()
                        ;
                    selection.collapse(true);
                    clone.moveToElementText(object);
                    clone.setEndPoint('EndToEnd', selection);

                    caret_pos = clone.text.length;
                }

                return caret_pos;
            } // end of get_caret_pos

        ,   setCaretPos: function(idObject, caret_pos) {
                var
                        object = this.object(idObject).get(0)
                    ;
                object.focus();

                if (object.selectionStart) {
                    // Gecko
                    object.setSelectionRange(caret_pos, caret_pos);
                } else {
                    // IE?
                }
            } // end of set_caret_pos

        ,   inSet: function(value, set) {
                for (var key in set) {
                    if (value == set[key]) {
                        return true;
                    }
                }
                return false;
            } // end of in_set

    }; // --- end of new namespace ---
    
    // --- end of pre-phase

})(jQuery);
