<?php function content($data) { ?>
<?php global $is_admin; ?>
<?php global $authorized; ?>
<?php global $curcontest; ?>
<?php if (_data('contest') != $curcontest): ?>
<h3>«адачи<?='' != _data('requested_contest_name') ? ': '._data('requested_contest_name') : ''?></h3>
<?php endif; ?>
<?php          
    //есть том
    if (_has('volume') && _has('problems') && 0 < count(_data('problems'))):
    // todo: пр€тать OK/попыток
?>
          <table>
            <tr>
              <th>«адача </th>
              <th>Ќазвание </th>
              <th>¬опросы</th>
<?php if (1 == $authorized): ?>              
              <th>–езультат </th>
<?php endif; ?>              
<?php if (_settings_show_problem_stats || 1 == $is_admin): ?>
              <th>OK/ѕопыток</th>
              <th>%</th>
            </tr>
<?php endif; ?>            
<?php
    $problems = _data('problems');
      //перебираем задачи
      $nth = false;
      while (list($key, $instance) = each($problems)):
?>      
            <tr<?=$nth ? ' class="s"' : ''?>>
<?php            
        $nth = !$nth;
        //получить ID задачи - игнорируютс€ первые пробелы
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
                  ::<a href="./questions.php?taskId=<?=$instance->TaskID?>">вопросы</a>                
<?php
  //в случае авторизации как админ, следует показать количество новых вопросов
  if ($is_admin == 1 && $instance->questions > 0):
?>                  
                  &nbsp;<?=$instance->questions?> новых
<?php
  endif; //конец проверки на админскую авторизацию
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
            endif; //конец проверки решенности задачи
          else:
?>
              <td>&nbsp;</td>
<?php
          endif; //конец отрисовки €чейки со статусом задачи
        //пользователь не авторизован
        endif; //конец проверки авторизации пользовател€ (и отображени€ €чейки статуса задачи
?>
<?php if (_settings_show_problem_stats || 1 == $is_admin): ?>
              <td><?=$instance->Solved?>/<?=$instance->Attempt?></td>
<?php
        //считаем и отображаем проценты
        if ($instance->Attempt != '0'):
          $perc = '';
          $value = 100*$instance->Solved/$instance->Attempt;
          $state = 1;

          settype($value, 'string');

          //этот цикл дл€ того, чтобы получить дроби
          for ($j=0; $j<strlen($value); $j++) {
            $perc = $perc.$value[$j];
            if ($state==1) {
              if ($value[$j]=='.') { $state = 2; }
            } else { break; }
          }
?>
                <td><?=$perc?></td>
<?php
        //нет попыток - значит проценты посчитать нельз€
        else:
?>        
                <td>-</td>
<?php
        endif; //конец отображени€ процентов
?>
<?php endif; ?>
            </tr>
<?php
      endwhile; //конец отображени€ таблицы с задачами
?>
          </table>
<?php
    //том не указан
    elseif (_has('volumes') && 0 < count(_data('volumes'))):
?>
          <table>
            <tr>
              <th>“ом</th>
              <th>Ќазвание</th>
            </tr>
<?php
      //перебираем тома
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
      endwhile; //конец перебора томов
?>
          </table>
<?php else: ?>
<p class="message"> онтест не содержит задач, не задан, либо не активен в данный момент</p>          
<?php
    endif; //конец отображени€ данных, если том не указан
?>
<?php } ?>
