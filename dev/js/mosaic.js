// --- mosaic ---
// version of june 16, 2011

// should be run first

(function($) {

    if (window.mosaic) { return false; }

    window.mosaic = {

            settings: function() {

                return _settings;

            }

    };

    var
            _ANIMATION_DELAY    = 200
        ;

    var
            _settings = {
                    'animationDelay':   _ANIMATION_DELAY
            }
        ;

})(jQuery);