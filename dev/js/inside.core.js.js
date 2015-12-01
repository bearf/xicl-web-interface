// --- inside.core.js ---
// version of june 17, 2011

(function($) {

    // --- PRE-PHASE ---

    if (!window.inside) { throw('global object [inside] not found'); }
    if (!window.inside.core) { throw('global object [inside.core] not found'); }
    
    if  (window.inside.core.js) { return false; }

    // --- end of PRE-PHASE ---




    // --- PUBLIC GLOBAL METHODS ---

    window.inside.core.js = {

        // Run "fn" catching errors
        // If no "fn" found then execute callback immediately.
        // Assuming "callback" is the last argument to "fn".
        // "fn" is always called as this == window
        executeSafe: function(fn, callback) {
            return function() {
                if ('undefined' == typeof fn) {
                    if ('undefined' != typeof callback) { callback(); }

                    return undefined;
                } else {
                    try {
                        var
                                _arguments = []
                            ;
                        for (var i=0; i<arguments.length; i++) {
                            _arguments.push(arguments[i]);
                        }
                        if ('undefined' != typeof callback) { _arguments.push(callback); }

                        return fn.apply(undefined, _arguments);
                    } catch(exception) {
                        alert(exception);
                    }
                }
            }
        } // executeSafe

    };

    // --- end of PUBLIC GLOBAL METHODS ---
    




    // --- INIT ---

    Function.prototype.bind = function (scope) {
        var fn = this;
        return function () {
            return fn.apply(scope, arguments);
        };
    };            
            
    // --- end of INIT ---

})(jQuery);