<?php
function content($data) {
$requested_contest_name = _data('requested_contest_name');
//$contest = _data('contest');
//$kind = _data('kind');
//$indexes = _data('indexes');
//$names = _data('names');
$status = _data('status');
//$first = _data('first');
//$teams = _data('teams');
//$pagesize = _data('pagesize');
//$rowcount = _data('rowcount');
//$page = _data('page');
$params = _data('params');
$topparams = _data('topparams');
$top_submit = _data('top_submit');
$bottom_submit = _data('bottom_submit');
global $curcontest; 
global $is_admin;
if (0 == count($status)):
?>
<?php if (_data('contest') != $curcontest): ?>
<h3>Статус посылок: <?=$requested_contest_name?></h3>
<?php endif; ?>
<p class="message">При заданных условиях поиска не найдено ни одного отправленного решения</p>
<?php
else:
?>
<?php if (_data('contest') != $curcontest): ?>
<h3>Статус посылок: <?=_data('requested_contest_name')?></h3>
<?php endif; ?>
          <table>
            <tr>
              <th>ID</th>
              <th>Дата</th>
              <th>Задача</th>
              <th>Пользователь</th>
              <th>Язык</th>
              <th>Статус</th>
<?php
if (_settings_show_submit_info || 1 == $is_admin):
?>
              <th>Время</th>
              <th>Память</th>
<?php
endif;
?>              
            </tr>
<?php
$nth = false;
while (list($key, $f) = each($status)):
    $nth = !$nth;
?>
            <tr <?=!$nth ? 'class="s"' : ''?>>
                <?php if (1 == $is_admin): ?>
                    <td><a href="./source.php?submitid=<?=$f->SubmitID?>&top=<?=_data('first_submit').$params?>" title="Исходный код"><?=$f->SubmitID?></a></td>
                <?php else: ?>
                    <td><?=$f->SubmitID?></td>
                <?php endif; ?>
              <td><?=$f->SubmitTime?></td>
              <td><a href="./problem.php?contest=<?=_data('contest')?>&amp;problem=<?=$f->ProblemID?>"><?=$f->ProblemID?></a></td>
              <td><?php userlink($f->Nickname, $f->ID); ?></td>
              <td><?=$f->Ext?></td>
<?php
    // определение цвета ячейки статуса
    $color = 0 != $f->StatusID ? ' ' : (0 != $f->ResultID ? ' class="wa"' : ' class="ok"');
?>
              <td<?=$color?>>
                <?=$f->Message?>
              </td>
<?php
    if (_settings_show_submit_info || 1 == $is_admin):
?>
              <td><?=(('0' === $f->TotalTime) ? '-' : $f->TotalTime.' ms')?></td>
              <td><?=(('0' === $f->TotalMemory) ? '-' : $f->TotalMemory.' kb')?></td>
<?php
    endif;
?>              
            </tr>
<?php
endwhile; //конец прохода по строкам таблицы статуса
?>
          </table>

          <table class="links">
            <tr>
<?
if (-1 != $top_submit):
?>
              <td style="width:33%">
                <a href="status.php?top=<?=$top_submit?><?=$params?>">
                  ::вверх на 10
                </a>
              </td>
<?
else:
?>
              <td style="width:33%">
                &nbsp;
              </td>
<?
endif;
?>
              <td style="width:34%">
                <a href="status.php?<?=$topparams?>">
                  ::наверх
                </a>
              </td>
<?
if (-1 != $bottom_submit):
?>
              <td style="width:33%">
                <a href="status.php?top=<?=$bottom_submit?><?=$params?>">
                  ::вниз на 10
                </a>
              </td>
<?
else:
?>
              <td style="width:33%">
                &nbsp;
              </td>
<?
endif;
// конец отображения таблицы ссылок
?>
            </tr>
          </table>
<?php
endif; // конец проверки наличия сабмитов
?>          
<?php
}
?>
