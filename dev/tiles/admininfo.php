<?php function content($data) { ?>
<?php $new_questions = _data('new_questions'); ?>
<?php $old_questions = _data('old_questions'); ?>
<?php $print_queue = _data('print_queue'); ?>
          <p>
          ::<a href="#new_questions">новые вопросы</a>
          &nbsp;::<a href="#old_questions">старые вопросы</a>
          &nbsp;::<a href="#print_queue">очередь печати</a>
          </p>
          <hr />
          <h3 id="new_questions">Ќовые вопросы</h3>    
<?php    
//выводим сообщение, что вопросов нет
if (count($new_questions) == 0):
?>
          <p>Ќет вопросов дл€ отображени€.</p>
<?php                  
else:
?>
          <table style="width:100%;">
            <tr>
              <th>ѕользователь</th>
              <th>«адача</th>
              <th>¬опрос</th>
              <th>ќперации</th>
            </tr>
<?php
$nth = false;
//цикл по запис€м в таблице вопросов
while (list($key, $value) = each($new_questions)):
    //определение цвета строки
    if (!$nth):
?>
            <tr>
<?php
    else:
?>
            <tr class="s">
<?php
    endif; //конец определени€ цвета строки
    $nth = !$nth;
?>
              <td style="width:10%">
                <?php userlink($value->userNickName, $value->userId); ?>
              </td>
              <td style="width:20%">
                <?=$value->taskName?>
              </td>
              <td style="width:45%">
                <?=$value->question?>
              </td>
              <td style="width:25%">
                <sup>
                  ::<a href="./editqform.php?questionId=<?=$value->questionId?>">ответить</a>
                  &nbsp;
                  ::<a href="./deleteq.php?questionId=<?=$value->questionId?>">удалить</a>
                  &nbsp;
<?php
            //если вопрос не публичный - показать галочку "сделать публичным"
            if ($value->isPublic == 0):
?>
                  ::<a href="./publicq.php?questionId=<?=$value->questionId?>&public=1">отметить как публичный</a>
<?php
            //иначе показать галочку "сделать непубличным"
            else:
?>
                  ::<a href="./publicq.php?questionId=<?=$value->questionId?>&public=0">отметить как непубличный</a>
<?php
            endif; //конец проверки общедоступности вопроса
?>                  
                </sup>
              </td>
            </tr>
<?php
endwhile; //конец цикла по запис€м в таблице вопросов
?>
          </table>
<?php
endif; // конец проверки на наличие вопросов
?>          
          <hr />
          <h3 id="old_questions">—тарые вопросы</h3>    
<?php    
//выводим сообщение, что вопросов нет
if (count($old_questions) == 0):
?>
          <p>Ќет вопросов дл€ отображени€.</p>
<?php                  
else:
?>
          <table style="width:100%;">
            <tr>
              <th>ѕользователь</th>
              <th>«адача</th>
              <th>¬опрос</th>
              <th>ќперации</th>
            </tr>
<?php
    //цикл по запис€м в таблице вопросов
    $nth = false;
    while (list($key, $value) = each($old_questions)):
        //определение цвета строки
        if (!$nth):
?>
            <tr>
<?php
        else:
?>
            <tr class="s">
<?php
    endif; //конец определени€ цвета строки
        $nth = !$nth;
?>
              <td style="width:10%">
                <?php userlink($value->userNickName, $value->userId); ?>
              </td>
              <td style="width:20%">
                <?=$value->taskName?>
              </td>
              <td style="width:45%">
                <?=$value->question?>
              </td>
              <td style="width:25%">
                <sup>
                  ::<a href="./editqform.php?questionId=<?=$value->questionId?>">ответить</a>
                  &nbsp;
                  ::<a href="./deleteq.php?questionId=<?=$value->questionId?>">удалить</a>
                  &nbsp;
<?php
            //если вопрос не публичный - показать галочку "сделать публичным"
            if ($value->isPublic == 0):
?>
                  ::<a href="./publicq.php?questionId=<?=$value->questionId?>&public=1">отметить как публичный</a>
<?php
            //иначе показать галочку "сделать непубличным"
            else:
?>
                  ::<a href="./publicq.php?questionId=<?=$value->questionId?>&public=0">отметить как непубличный</a>
<?php
            endif; //конец проверки общедоступности вопроса
?>                  
                </sup>
              </td>
            </tr>
<?php
    endwhile; //конец цикла по запис€м в таблице вопросов
?>
          </table>
<?php
endif; // конец проверки на наличие вопросов
?>          
          <hr />
          <h3 id="print_queue">ќчередь печати</h3>
<?php    
//выводим сообщение, что вопросов нет
if (count($print_queue) == 0):
?>
          <p>ќчередь пуста.</p>
<?php                  
else:
?>
          <table style="width:100%;">
            <tr>
              <th style="width:30%">ѕользователь</th>
              <th style="width:30%">«адача</th>
              <th style="width:5%">—татус</th>
              <th style="width:45%">ќперации</th>
            </tr>
<?php
    //цикл по запис€м в очереди печати
    $nth = false;
    while (list($key, $value) = each($print_queue)):
      //определение текста в колонке статус
      $status = 'ќжидание...';
      $status_color = '';
      if (3 == $value->isPrinted) {
        $status = 'OK';
        $status_color = ' style="color:green" ';
      }
      else if (4 == $value->isPrinted) {
        $status = 'Failed';
        $status_color = ' style="color:red" ';
      }
      else if (1 == $value->isPrinted) {
        $status = '¬ очереди';
        $status_color = ' style="color:gray" ';
      }
      
      //определение цвета строки
      if (!$nth):
?>
            <tr>
<?php
      else:
?>
            <tr class="s">
<?php
      endif; //конец определени€ цвета строки
        $nth = !$nth;
?>
              <td>
                <?php userlink($value->userNickName, $value->userId); ?>
              </td>
              <td>
                <?=$value->taskName?>
              </td>
              <td<?=$status_color?>>
                <?=$status?>
              </td>
              <td>
                <sup>
                  ::<a href="./deletep.php?printId=<?=$value->printId?>">удалить</a>
                  &nbsp;
<?php
      if ((1 == $value->isPrinted) || (3 == $value->isPrinted) || (4 == $value->isPrinted)):
?>                  
                  ::<a href="./clearp.php?printId=<?=$value->printId?>">печатать заново</a>
<?php
      endif;
?>                  
                </sup>
              </td>
            </tr>
<?php
    endwhile; //конец цикла по запис€м в очереди печати
endif; // конец проверки на наличие записей в очереди печати 
?>
          </table>
<?php } ?>
