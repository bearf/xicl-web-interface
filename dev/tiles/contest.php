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
          <p class="message">В базе данных не турниров.</p>
<?php
else:
?>
          <table>
            <tr>
              <th class="c">ID</th>
              <th>Название</th>
              <th class="c">Дата начала</th>
              <th>Дата окончания</th>
              <th>Статус</th>
            </tr>
<?php $nth = false; //поехали выводить контесты ?>
<?php while (list($key, $value) = each($contests)): ?>
            <tr<?=$value->ContestID==$curcontest ? ' class="active"' : ($nth ? ' class="s"' : '')?>>
<?php $nth = !$nth; ?>
              <td class="c"><?=$value->ContestID?></td>
              <td>
<?php
    if ($value->Status == 1): // проверка на статус контеста
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
    endif; //конец проверки того, каков статус турнира
?>
              </td>
              <td><?=$value->Start?></td>
              <td><?=$value->Finish?></td>
<?php
    if ($value->Status == 1): // проверка на статус контеста
?>
              <td>Активен</td>
<?php
    elseif ($value->Status == 2):
?>
              <td>Завершен</td>
<?php
    else:
?>
              <td>Не начат</td>
<?php
    endif;
?>
            </tr>
<?php
    endwhile;
?>
          </table>
<?php
endif; //конец проверки на наличие данных
?>
<?php } ?>
