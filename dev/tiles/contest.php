<?php function content($data) { ?>
<?php global $curcontest; ?>
<?php
if (_has('message')):
?>
          <p class="message"><?=_data('message')?>
          <hr />
<?php
endif;
$contests = _data('contests');
if (count($contests) == 0):
?>
          <p class="message">� ���� ������ �� ��������.</p>
<?php
else:
?>
          <table>
            <tr>
              <th class="c">ID</th>
              <th>��������</th>
              <th class="c">���� ������</th>
              <th>���� ���������</th>
              <th>������</th>
            </tr>
<?php $nth = false; //������� �������� �������� ?>
<?php while (list($key, $value) = each($contests)): ?>
            <tr<?=$value->ContestID==$curcontest ? ' class="active"' : ($nth ? ' class="s"' : '')?>>
<?php $nth = !$nth; ?>
              <td class="c"><?=$value->ContestID?></td>
              <td>
<?php
    if ($value->Status == 1): // �������� �� ������ ��������
?>
                <a href="contest.php?selcontest=<?=$value->ContestID?>">
                  <?=$value->Name?>
                </a>
<?php
    elseif ($value->Status == 2):
?>
                <a href="standing.php?contest=<?=$value->ContestID?>">
                  <?=$value->Name?>
                </a>
<?php
    else:
?>
                <?=$value->Name?>
<?php
    endif; //����� �������� ����, ����� ������ �������
?>
              </td>
              <td><?=$value->Start?></td>
              <td><?=$value->Finish?></td>
<?php
    if ($value->Status == 1): // �������� �� ������ ��������
?>
              <td>�������</td>
<?php
    elseif ($value->Status == 2):
?>
              <td>��������</td>
<?php
    else:
?>
              <td>�� �����</td>
<?php
    endif;
?>
            </tr>
<?php
    endwhile;
?>
          </table>
<?php
endif; //����� �������� �� ������� ������
?>
<?php } ?>
