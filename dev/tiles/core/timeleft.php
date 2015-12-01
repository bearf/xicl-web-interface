<?php function timeleft($content_tile_name) { ?>
<?php
global $is_admin;
global $contestname;
// Ќазначение: отображение меню
// »спользование: должен подключатьс€ в каждой странице сайта
if (_settings_show_tournament_menu && 'tournament' == get_site_branch($content_tile_name)): // страницы tournament ?>        
    <h2 id="h2-tournament" class="active"><?=_data('srvtime')?></h2>
<?php else: // страницы contest ?>
    <h2 class="active"><?=_data('srvtime')?></h2>
<?php endif // конец проверки на текущую ветку сайта ?>
<?php } ?>
