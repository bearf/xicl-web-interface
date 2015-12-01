<?php function content($data) { ?>
<?php global $authorized; ?>
<?php global $is_admin; ?>
<?php $questions = _data('questions'); ?>
<h3>Вопросы по задаче: <?=_data('taskName')?></h3>
<hr />
<p>Вопрос должен быть составлен в такой форме, чтобы на него можно было ответить "Да" или "Нет". Вопросы, на которые нельзя ответить подобным образом, а также вопросы, ответы на которые содержатся в условии, будут получать ответ "Без комментариев".</p>
<p>Вопросы (и ответы), добавленные Вашей командой, будут видимы исключительно Вам, кроме того случая, когда вопрос действительно важен для понимания условия задачи, или если по изучении вопроса жюри пришло к выводу, что условие либо тесты содержат ошибку. В этом случае вопрос помечается как "общий" и становится видимым всем командам.</p>
<hr />
<?php if ($authorized == 1): ?>
<p>::<a href="./addqform.php?taskId=<?=_data('taskId')?>">добавить новый вопрос</a>&nbsp;</p>
<?php else: ?>
<p class="message">Добавлять новые вопросы могут только зарегистрированные пользователи.</p>
<?php endif; ?>    
<hr />
<?php if (0 == count($questions)): //выводим сообщение, что вопросов нет ?>
<p class="message">Нет вопросов для отображения.</p>
<?php endif; ?>
<?php
    //цикл по записям в таблице вопросов
    while (list($key, $instance) = each($questions)):
        //сколько строк по высоте будет запись
        $row_span = 0;
        if ($instance->question) { $row_span++; }
        if ($instance->result > 0) { $row_span++; }
        if ($instance->comment) { $row_span++; }
      
        //определяем, какие из чекбоксов будут помечены
        $yes_checkbox_value = $instance->result == 1 ? ' checked="checked ' : '';
        $no_checkbox_value = $instance->result == 2 ? ' checked="checked ' : '';
        $nocomment_checkbox_value = $instance->result == 3 ? ' checked="checked" ' : '';
        $class = '0' === $instance->result && ('' === $instance->comment || null == $instance->comment) && $is_admin == 1 ? ' new' : '';
        
        //признак того, что строка была переведена
        $new_line = false;
?>
          <table class="forum<?=$class?>">
<?php
        //в случае админской авторизации показываем панель управления
        if (1 == $is_admin):
?>
            <tr>
              <td colspan="2" class="actions">
                <sup>
                  ::<a href="./editqform.php?questionId=<?=$instance->questionId?>">ответить</a>
                  &nbsp;
                  ::<a href="./deleteq.php?questionId=<?=$instance->questionId?>">удалить</a>
                  &nbsp;
<?php
            //если вопрос не публичный - показать галочку "сделать публичным"
            if ($instance->isPublic == 0):
?>
                  ::<a href="./publicq.php?questionId=<?=$instance->questionId?>&public=1">отметить как публичный</a>
<?php
            //иначе показать галочку "сделать непубличным"
            else:
?>
                  ::<a href="./publicq.php?questionId=<?=$instance->questionId?>&public=0">отметить как непубличный</a>
<?php
            endif; //конец проверки общедоступности вопроса
?>                  
                </sup>
              </td>
            </tr>
<?php
        endif; //конец обработки панели управления
?>          
            <tr>
              <td rowspan="<?=$row_span?>" class="userdate">
                <?php userlink($instance->userNickName, $instance->userId); ?>
                <sup><?=$instance->dateTime?></sup>
<?php
        if ($instance->isPublic == 1):                
?>
                <sup>публичный</sup>
<?php
        endif;
?>                
              </td>
<?php
        if ($instance->question):
?>
              <td class="question">
                <?=$instance->question?>
              </td>
            </tr>
<?php
            $new_line = true;
        endif;
        if ($instance->result > 0):
            if (!$new_line):
                $new_line = true;
            else:
?>
            <tr>
<?php
            endif;
?>
              <td class="controls">
                <input type="checkbox" class="checkbox" name="yes" <?=$yes_checkbox_value?> disabled="disabled" /> Да&nbsp;&nbsp;
                <input type="checkbox" class="checkbox" name="no" <?=$no_checkbox_value?> disabled="disabled" /> Нет&nbsp;&nbsp;
                <input type="checkbox" class="checkbox" name="nocomment" <?=$nocomment_checkbox_value?> disabled="disabled" /> Без комментариев&nbsp;&nbsp;
              </td>
            </tr>
<?php   endif; ?>
<?php   if ($instance->comment): ?>
<?php       if (!$new_line): ?>
<?php           $new_line = true; ?>
<?php       else: ?>
            <tr>
<?php       endif; ?>
              <td class="comment">
                <?=$instance->comment?>
              </td>
            </tr>
<?php   endif; ?>
<?php   if (!$new_line): //проверка, что нужно принудительно закрыть строку?>
            </tr>
<?php   endif; //конец проверки на необходимость закрыть строку ?>
          </table>
<?php endwhile; //конец цикла по записям в таблице вопросов ?>          
<?php } ?>
