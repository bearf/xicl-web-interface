<?php function timeleft($content_tile_name) { ?>
<?php
global $is_admin;
global $contestname;
// ����������: ����������� ����
// �������������: ������ ������������ � ������ �������� �����
if (_settings_show_tournament_menu && 'tournament' == get_site_branch($content_tile_name)): // �������� tournament ?>        
    <h2 id="h2-tournament" class="active"><?=_data('srvtime')?></h2>
<?php else: // �������� contest ?>
    <h2 class="active"><?=_data('srvtime')?></h2>
<?php endif // ����� �������� �� ������� ����� ����� ?>
<?php } ?>
