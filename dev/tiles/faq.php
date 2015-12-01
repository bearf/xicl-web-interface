<?php function content($data) { ?>
<?php global $is_admin; ?>
<?php $faq = _data('faq'); ?>
          <h3>Вопрос-ответ</h3>
          <hr />
          <p>Эта страница предназначена для вопросов по организации турнира, устройству сайта и прочим, не имеющим отношения к задачам в contest-системе. Сообщения не попадающие под указанные выше категории, будут удаляться немедленно.</p>                        
          <hr />
          <form name="faqform" action="./insertq.php" method="post">
            <!-- параметр page уже установлен -->
            <input type="hidden" name="page" value="<?=_data('page')?>" />
            <table class="enter">
              <tr>
                <td class="top">вопрос</td>
                <td>
                  <textarea name="question"
                    wrap="virtual"
                    cols="40"
                    rows="10"><?=_data('question')?></textarea>
              </tr>
              <tr>
                <td>&nbsp;</td><td  class="c">
                  <input type="submit" name="submit" class="submit" value="отправить вопрос" />
                </td>
              </tr>
            </table>
          </form>
          <hr />
<?php    
//вопросов нет
if (0 == count($faq)):
?>
          <p>Нет вопросов.</p>
<?php    
else:
    // цикл по вопросам, при этом пропускаем ненужные страницы
    while (list($key, $f) = each($faq)):
?>
          <p class="b">
            Q: <?=$f->question?>
          </p>
          <p>
            A: 
<?php
          if ($f->answer == ''):
?>            
            на этот вопрос пока нет ответа.
<?php
          else:
?>          
            <?=$f->answer?>
<?php
          endif;
?>            
          </p>
<?php
          if ($is_admin == 1):
?>
          <p>
            ::<a href="./editanswer.php?faqid=<?=$f->faqid?>">редактировать ответ</a>
            &nbsp;
            ::<a href="./deletefaq.php?faqid=<?=$f->faqid?>">удалить вопрос</a>
          </p>
<?php
          endif;
?>
          <hr />
<?php        
    endwhile; //конец цикла по вопросам
      
      //отображаем список страниц

      if (_data('pagecount') > 0):
?>
          <p class="c">
            страницы:&nbsp;
<?php
        for ($i=1; $i<=_data('pagecount'); $i++)
          if (_data('page')==$i):
?>
            <strong><?=$i?></strong>
<?php
          else:
?>
            <a href="./faq.php?page=<?=$i?>"><?=$i?></a>
<?php
          endif; //конец вывода номера страницы и цикла по номерам
?>
          </p>
<?php
      endif; //конец вывода номеров страниц          
    endif; //конец обработки отсутствия вопросов
?>
<?php } ?>
