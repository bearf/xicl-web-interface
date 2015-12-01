<?php function toolbar($content_tile_name = null) { ?>
<span class="noheader"><a href="javascript:void(0);" onclick="toggleHeader();" title="показать заголовок"><img src="./graphic/maximize.png" alt="&gt;" width="22" height="16" /></a></span>
<span class="header"><a href="javascript:void(0);" onclick="toggleHeader();" title="скрыть заголовок"><img src="./graphic/minimize.png" alt="&lt;" width="22" height="16" /></a></span>
<?php if ('standing' != $content_tile_name): ?>
    <span class="noleft"><a href="javascript:void(0);" onclick="toggleLeft();" title="скрыть меню"><img src="./graphic/left.png" alt="&lt;" width="16" height="16" /></a></span>
    <span class="tbleft"><a href="javascript:void(0);" onclick="toggleLeft();" title="показать меню"><img src="./graphic/right.png" alt="&rt;" width="16" height="16" /></a></span>
<?php endif; ?>
<?php } ?>
