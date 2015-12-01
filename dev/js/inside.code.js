// --- inside.code ---
// version of june 17, 2011

(function($) {

    // --- pre-phase ---

    if (!window.inside) { throw('global object [inside] not found'); }
    
    if  (window.inside.code) { return false; }

    window.inside.code = {

            KEY_UP                  : 38
        ,   KEY_DOWN                : 40
        ,   KEY_LEFT                : 37
        ,   KEY_RIGHT               : 39
        ,   KEY_HOME                : 36
        ,   KEY_END                 : 35
        ,   KEY_PAGE_DOWN           : 34
        ,   KEY_PAGE_UP             : 33
        ,   KEY_ENTER               : 13
        ,   KEY_TAB                 : 9
        ,   KEY_ESC                 : 27

    };
    
    // --- end of pre-phase

})(jQuery);
