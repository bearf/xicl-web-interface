<?php
function content($data) {
global $curuserid;
$requested_contest_name = _data('requested_contest_name');
$monitor_time = _data('monitor_time');
$contest = _data('contest');
$kind = _data('kind');
$indexes = _data('indexes');
$names = _data('names');
$problems = _data('problems');
$standing = _data('standing');
$first = _data('first');
$teams = _data('teams');
$pagesize = _data('pagesize');
$rowcount = _data('rowcount');
$page = _data('page');
if (0 == $rowcount):
?>
<p class="message">������� ����������� �������� ���� �����</p>
<?php
else:
if ($requested_contest_name != ''):
?>
          <h3>����������: <?=$requested_contest_name?><?=$monitor_time?>&nbsp;::<a href="javascript:refreshMonitor('table.php', {'div':1});">��������</a>&nbsp;::<a href="javascript:refreshMonitor('table.php', {'div':2});">���������</a>&nbsp;::<a href="javascript:refreshMonitor('table.php', {'div':0});">���</a></h3>
<?php
else:
endif;
?>
          <table class="standing">
            <tr>
              <th>#</th>
              <th>������������</th>
              <th>������ / ������</th>
              <th>OK</th>
              <th>�����</th>
           </tr>
<?php
//������� ������
$index = 0;
while (list($key, $f) = each($standing)):
    $index++;
?>    
                  <tr <?=$f->ID==$curuserid ? 'class="active"' : ($index%2==0 ? 'class="s"' : '')?>>
                    <td class="c"><?=$index+$first?></td>
                    <td class="user-info">
                        <?php userlink($teams[$f->ID]->nickname, $f->ID); ?>
                    </td>
                      <td style="white-space:normal;">
                          <?php echo $teams[$f->ID]->members; ?>
                      </td>
                    <td class="c"><?=isset($f->Solved) && $f->Solved ? $f->Solved : 0?></td>
                      <?php
        if (!isset($penalty) || !$penalty) { $penalty = 0; }
        $penalty = $f->Penalty/60;
        settype($penalty,'integer');
                      ?>
                      <td class="c"><?=$penalty?></td>
                  </tr>
<?
endwhile; //����� ������� �� �������
?>
          </table>
<?
endif; // ����� ���� �� ������� �������
?>
    <script type="text/javascript">
      window.setTimeout('refreshMonitor("table.php")', 60000);
    </script>
<?php } ?>

