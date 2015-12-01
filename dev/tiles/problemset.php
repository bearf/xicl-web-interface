<?php function content($data) { ?>
<?php global $is_admin; ?>
<?php global $authorized; ?>
<?php global $curcontest; ?>
<?php if (_data('contest') != $curcontest): ?>
<h3>������<?='' != _data('requested_contest_name') ? ': '._data('requested_contest_name') : ''?></h3>
<?php endif; ?>
<?php          
    //���� ���
    if (_has('volume') && _has('problems') && 0 < count(_data('problems'))):
    // todo: ������� OK/�������
?>
          <table>
            <tr>
              <th>������ </th>
              <th>�������� </th>
              <th>�������</th>
<?php if (1 == $authorized): ?>              
              <th>��������� </th>
<?php endif; ?>              
<?php if (_settings_show_problem_stats || 1 == $is_admin): ?>
              <th>OK/�������</th>
              <th>%</th>
            </tr>
<?php endif; ?>            
<?php
    $problems = _data('problems');
      //���������� ������
      $nth = false;
      while (list($key, $instance) = each($problems)):
?>      
            <tr<?=$nth ? ' class="s"' : ''?>>
<?php            
        $nth = !$nth;
        //�������� ID ������ - ������������ ������ �������
        $problem_id = $instance->ProblemID;
        $pid = '';
        for ($j=strlen($problem_id); $j>0; $j--)
          if ($problem_id[$j-1] != ' ')
            $pid = $problem_id[$j-1].$pid;

        settype($problem_id, 'integer');
?>
              <td class="c"><?=$pid?></td>
              <td>
                <a href="problem.php?problem=<?=$pid?>&contest=<?=_data('contest')?>"><?=$instance->Name?></a>
              </td>
              <td>
                <sup>
                  ::<a href="./questions.php?taskId=<?=$instance->TaskID?>">�������</a>                
<?php
  //� ������ ����������� ��� �����, ������� �������� ���������� ����� ��������
  if ($is_admin == 1 && $instance->questions > 0):
?>                  
                  &nbsp;<?=$instance->questions?> �����
<?php
  endif; //����� �������� �� ��������� �����������
?>
                </sup>
              </td>
<?php
        if ($authorized == 1):
          if (isset($instance->OK)):
            if ($instance->OK):
?>
              <td class="c">+</td>
<?php
            elseif($instance->MyAttempt):
?>
              <td class="c">-</td>
            <?php else: ?>
                <td>&nbsp;</td>
<?php
            endif; //����� �������� ���������� ������
          else:
?>
              <td>&nbsp;</td>
<?php
          endif; //����� ��������� ������ �� �������� ������
        //������������ �� �����������
        endif; //����� �������� ����������� ������������ (� ����������� ������ ������� ������
?>
<?php if (_settings_show_problem_stats || 1 == $is_admin): ?>
              <td><?=$instance->Solved?>/<?=$instance->Attempt?></td>
<?php
        //������� � ���������� ��������
        if ($instance->Attempt != '0'):
          $perc = '';
          $value = 100*$instance->Solved/$instance->Attempt;
          $state = 1;

          settype($value, 'string');

          //���� ���� ��� ����, ����� �������� �����
          for ($j=0; $j<strlen($value); $j++) {
            $perc = $perc.$value[$j];
            if ($state==1) {
              if ($value[$j]=='.') { $state = 2; }
            } else { break; }
          }
?>
                <td><?=$perc?></td>
<?php
        //��� ������� - ������ �������� ��������� ������
        else:
?>        
                <td>-</td>
<?php
        endif; //����� ����������� ���������
?>
<?php endif; ?>
            </tr>
<?php
      endwhile; //����� ����������� ������� � ��������
?>
          </table>
<?php
    //��� �� ������
    elseif (_has('volumes') && 0 < count(_data('volumes'))):
?>
          <table>
            <tr>
              <th>���</th>
              <th>��������</th>
            </tr>
<?php
      //���������� ����
    $volumes = _data('volumes');
      $nth = false;
      while (list($key, $instance) = each($volumes)):
?>      
            <tr<?=$nth ? ' class="s"' : ''?>>
<?php            
        $nth = !$nth;
?>
            <td class="c"><?=$instance->Volume_Brief?></td>
            <td>
              <a href="problemset.php?contest=<?=_data('contest')?>&volume=<?=$instance->Volume_ID?>">
                <?=$instance->Volume_Name?>
              </a>
            </td>
          </tr>
<?
      endwhile; //����� �������� �����
?>
          </table>
<?php else: ?>
<p class="message">������� �� �������� �����, �� �����, ���� �� ������� � ������ ������</p>          
<?php
    endif; //����� ����������� ������, ���� ��� �� ������
?>
<?php } ?>
