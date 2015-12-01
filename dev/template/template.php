<?php function template($content_tile_name, $data) { ?>
<?php require_once dirname(__FILE__) . '/../tiles/'.$content_tile_name.'.php'; ?>
<?php before($content_tile_name); ?>
    <div id="header" class="right"><div class="right-inner">
        <?php top(); ?>    
    </div></div>
    <div id="content" class="right"><div class="right-inner" style="float:_left">
        <?php content($content_tile_name, $data); ?>
    </div></div>
    <div id="homelink" class="left"><div class="left-inner">
        <a href="./" title="на главную" id="home"></a>
    </div></div>
    <div id="timeleft" class="left"><div class="left-inner">
        <?php timeleft($content_tile_name); ?>
    </div></div>
    <?php if ('standing' != $content_tile_name && 'table' != $content_tile_name): ?>
        <div id="control" class="left"><div class="left-inner">
            <?php menu($content_tile_name); ?>
        </div></div>
    <?php endif; ?>
    <div id="stuff" class="right"><div class="right-inner">
        <?php stuff($content_tile_name); ?>
    </div></div>
    <div id="toolbar">
        <?php toolbar($content_tile_name); ?>
    </div>
    <div id="support" class="left"><div class="left-inner">
        <?php support(); ?>
    </div></div>
    <div id="contacts" class="right"><div class="right-inner">
        <?php contacts(); ?>
    </div></div>
    <script type="text/javascript">
        (function($) {
            var
                    menuWidth   = $('#menu').width()
                ,   on          = false
                ;
            $(document.body).mousemove(function(e) {
                if ($(document.body).hasClass('noleft')) {
                    if (!on) {
                        defMargin(true);
                    }
                    if ($(e.target).closest('#control').size() && !on) {
                        on = true;
                        $('#control').animate(
                                { 'marginLeft': defMargin() + menuWidth + 'px' }
                            ,   200
                        );
                    } if (!$(e.target).closest('#control').size() && on) {
                        on = false;
                        $('#control').animate(
                                { 'marginLeft': defMargin() + 'px' }
                            ,   200
                        );
                    }
                } else {
                    defMargin(true);
                }
            });
        })(jQuery);
    </script>
<?php after(); ?>
<?php die(); ?>
<?php } ?>
<?php function pure($content_tile_name, $data) { ?>
<?php require_once('./tiles/'.$content_tile_name.'.php'); ?>
<?php before($content_tile_name); ?>
    <div id="pure">
    <?php content($content_tile_name, $data); ?>
    </div>
<?php after(); ?>
<?php die(); ?>
<?php } ?>
