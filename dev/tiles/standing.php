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
<p class="message">“аблица результатов контеста пока пуста</p>
<?php
else:
if ($requested_contest_name != ''):
?>
          <h3>–езультаты: <?=$requested_contest_name?><?=$monitor_time?>&nbsp;::<a href="javascript:refreshMonitor({'div':1});">студенты</a>&nbsp;::<a href="javascript:refreshMonitor({'div':2});">школьники</a>&nbsp;::<a href="javascript:refreshMonitor({'div':0});">все</a></h3>
<?php
else:
?>
          <h3>–езультаты<?=$monitor_time?>&nbsp;::<a href="javascript:refreshMonitor({'div':1});">студенты</a>&nbsp;::<a href="javascript:refreshMonitor({'div':2});">школьники</a>&nbsp;::<a href="javascript:refreshMonitor({'div':0});">все</a></h3>
<?php
endif;
?>
          <table class="standing">
            <tr>
<?php
if ($kind==1):
?>
              <th>#</th>
              <th>ѕользователь</th>
              <th class="c">OK</th>
              <th>ѕоследн€€ сдача</th>
<?php
elseif ($kind==2):
?>
              <th>#</th>
              <th>ѕользователь</th>
              <th class="c">OK</th>
              <th class="c">ѕоследн€€ сдача</th>
              <th>Ѕаллы</th>
<?php
else:
?>
              <th>#</th>
              <th>ѕользователь</th>
<?php
    if (3==$kind || 4==$kind):
        //контест третьего типа - надо вывести индексы задач
        for ($i=0; $i<count($indexes); $i++):
?>              
              <th><a class="white" href="problem.php?contest=<?=$contest?>&amp;problem=<?=$indexes[$i]?>" title="<?=$names[$i]?>"><?=$indexes[$i]?></a></th>
<?php
        endfor; //конец цикла по индексам задач
    endif; // конец обработки контеста третьего типа
?>
              <th>OK</th>
              <th>¬рем€</th>
<?php              
    if (3 != $kind):
?>              
              <th>ѕоследн€€ сдача</th>
<?php
    endif;
    
    if (2 == $kind || 4 == $kind):
?>
              <th>Ѕаллы</th>
<?php
    endif;
endif; //конец рисовани€ заголовка
?>
              </tr>
<?php
//выводим строки
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
    //контест типа 3/4 - надо писать индексы задач
    if ($kind == 3 || $kind == 4):
        //цикл по индексам задач
        for ($j=0; $j<count($indexes); $j++):
            //если задача решена с первой попытки - выводим +
            //если задача решена - выводим +<количество неудачных попыток>
            //попыток не было - выводим .
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
        endfor; //конец цикла по индексам задач
    endif; //конец проверки на необходимость отрисовки индексов задач
?>                    
                    <td class="c"><?=isset($f->Solved) && $f->Solved ? $f->Solved : 0?></td>
<?php
    // контест третьего или четвертого типа - нам нужно штрафное врем€
    if (3 == $kind || 4 == $kind):
        if (!isset($penalty) || !$penalty) { $penalty = 0; }
        $penalty = $f->Penalty/60;
        settype($penalty,'integer');
?>
                    <td class="c"><?=$penalty?></td>
<?php        
    endif; // конец вывода штрафного времени
    
    // в контестах всех типов кроме третьего нам нужно врем€ последней сдачи
    if (3 != $kind):
?>                    
                    <td class="c"><?=isset($f->Solved) && isset($f->LastAC) && $f->Solved>0 && $f->LastAC ? $f->LastAC : '-'?></td>
<?
    endif; // конец показа последней сдачи
    // в контестах второго и четвертого типов нам нужны баллы
    if (2 == $kind || 4 == $kind):
?>    
                    <td class="c"><?=isset($f->Points) && $f->Points ? $f->Points : '0'?></td>
<?php             
    endif; // конец показа баллов       
    // конец строки
?>
                  </tr>
<?
endwhile; //конец пробега по строкам
?>
          </table>
<?php
$params = '&contest='.$contest;
$npage = $rowcount / $pagesize;
if ($rowcount%$pagesize != 0) { $npage += 1; }
if ($npage > 0):
?>
          <hr />
          <p class="pages">cтраницы:
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
        endif; //конец вывода номера страницы
    endfor; // конец цикла по номерам страниц
?>
          </p>
<?
endif; //конец вывода номеров страниц
endif; // конец чека на наличие записей
?>
    <script type="text/javascript">
      window.setTimeout('refreshMonitor("standing.php")', 60000);
    </script>
<?php } ?>

