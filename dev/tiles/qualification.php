<?php
function content($data) {
global $curuserid; global $is_admin; global $curteamid;
$requested_contest_name = _data('requested_contest_name');
$monitor_time = _data('monitor_time');
$contest = _data('contest');
$kind = _data('kind');
$indexes = _data('indexes');
$names = _data('names');
$standing = _data('standing');
$first = _data('first');
$teams = _data('teams');
$pagesize = _data('pagesize');
$rowcount = _data('rowcount');
$page = _data('page');
?>

<a href="#" id="common" class="anchor"></a>
<h3>����������</h3>

<div id="common_">
    
    <p>���������� ����� ������������ �������. ������� �� ������������.
    
</div>

<a href="#" id="quotes" class="anchor"></a>
<h3>�����</h3>
<div id="quotes_">

    <p>���������� ����� ������������ �������. ������� �� ������������.

</div>

<?php } ?>

