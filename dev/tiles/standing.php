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
          <h3>����������: <?=$requested_contest_name?><?=$monitor_time?>&nbsp;::<a href="javascript:refreshMonitor({'div':1});">��������</a>&nbsp;::<a href="javascript:refreshMonitor({'div':2});">���������</a>&nbsp;::<a href="javascript:refreshMonitor({'div':0});">���</a></h3>
<?php
else:
?>
          <h3>����������<?=$monitor_time?>&nbsp;::<a href="javascript:refreshMonitor({'div':1});">��������</a>&nbsp;::<a href="javascript:refreshMonitor({'div':2});">���������</a>&nbsp;::<a href="javascript:refreshMonitor({'div':0});">���</a></h3>
<?php
endif;
?>
          <table class="standing">
            <tr>
<?php
if ($kind==1):
?>
              <th>#</th>
              <th>������������</th>
              <th class="c">OK</th>
              <th>��������� �����</th>
<?php
elseif ($kind==2):
?>
              <th>#</th>
              <th>������������</th>
              <th class="c">OK</th>
              <th class="c">��������� �����</th>
              <th>�����</th>
<?php
else:
?>
              <th>#</th>
              <th>������������</th>
<?php
    if (3==$kind || 4==$kind):
        //������� �������� ���� - ���� ������� ������� �����
        for ($i=0; $i<count($indexes); $i++):
?>              
              <th><a class="white" href="problem.php?contest=<?=$contest?>&amp;problem=<?=$indexes[$i]?>" title="<?=$names[$i]?>"><?=$indexes[$i]?></a></th>
<?php
        endfor; //����� ����� �� �������� �����
    endif; // ����� ��������� �������� �������� ����
?>
              <th>OK</th>
              <th>�����</th>
<?php              
    if (3 != $kind):
?>              
              <th>��������� �����</th>
<?php
    endif;
    
    if (2 == $kind || 4 == $kind):
?>
              <th>�����</th>
<?php
    endif;
endif; //����� ��������� ���������
?>
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
<?php
    //������� ���� 3/4 - ���� ������ ������� �����
    if ($kind == 3 || $kind == 4):
        //���� �� �������� �����
        for ($j=0; $j<count($indexes); $j++):
            //���� ������ ������ � ������ ������� - ������� +
            //���� ������ ������ - ������� +<���������� ��������� �������>
            //������� �� ���� - ������� .
            $valueIndex = 'A'.$indexes[$j];
            $value = $f->$valueIndex;
            $solvedClass = $value == 0 ? '' : ($value > 0 ? 'solved' : 'unsolved');
            $value = $value == 1 ? $value = '+' : ($value > 1 ? $value = '+'.($value-1) : ($value == 0 ? '.' : $value));
            $timeIndex = 'T'.$indexes[$j];
            $problem = $problems[$j];
            $division = 2 == $problem->division ? 'div2' : (1 == $problem->division ? 'div1' : '');
?>
                    <td class="c task <?=$solvedClass?> <?php echo $division; ?>"><?=$value?><?='solved' == $solvedClass ? '<br /><span class="time">'.solvedAt($f->$timeIndex).'</span>' : ''?></td>
<?php
        endfor; //����� ����� �� �������� �����
    endif; //����� �������� �� ������������� ��������� �������� �����
?>                    
                    <td class="c"><?=isset($f->Solved) && $f->Solved ? $f->Solved : 0?></td>
<?php
    // ������� �������� ��� ���������� ���� - ��� ����� �������� �����
    if (3 == $kind || 4 == $kind):
        if (!isset($penalty) || !$penalty) { $penalty = 0; }
        $penalty = $f->Penalty/60;
        settype($penalty,'integer');
?>
                    <td class="c"><?=$penalty?></td>
<?php        
    endif; // ����� ������ ��������� �������
    
    // � ��������� ���� ����� ����� �������� ��� ����� ����� ��������� �����
    if (3 != $kind):
?>                    
                    <td class="c"><?=isset($f->Solved) && isset($f->LastAC) && $f->Solved>0 && $f->LastAC ? $f->LastAC : '-'?></td>
<?
    endif; // ����� ������ ��������� �����
    // � ��������� ������� � ���������� ����� ��� ����� �����
    if (2 == $kind || 4 == $kind):
?>    
                    <td class="c"><?=isset($f->Points) && $f->Points ? $f->Points : '0'?></td>
<?php             
    endif; // ����� ������ ������       
    // ����� ������
?>
                  </tr>
<?
endwhile; //����� ������� �� �������
?>
          </table>
<?php
$params = '&contest='.$contest;
$npage = $rowcount / $pagesize;
if ($rowcount%$pagesize != 0) { $npage += 1; }
if ($npage > 0):
?>
          <hr />
          <p class="pages">c�������:
<?php
    for ($i=1; $i<=$npage; $i++):
        if ($page==$i):
?>
            <strong><?=$i?></strong>
<?php
        else:
?>
            <a href="standing.php?page=<?=$i?><?=$params?>"><?=$i?></a>
<?php
        endif; //����� ������ ������ ��������
    endfor; // ����� ����� �� ������� �������
?>
          </p>
<?
endif; //����� ������ ������� �������
endif; // ����� ���� �� ������� �������
?>
    <script type="text/javascript">
      window.setTimeout('refreshMonitor("standing.php")', 60000);
    </script>
<?php } ?>

