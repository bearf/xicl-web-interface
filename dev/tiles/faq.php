<?php function content($data) { ?>
<?php global $is_admin; ?>
<?php $faq = _data('faq'); ?>
          <h3>������-�����</h3>
          <hr />
          <p>��� �������� ������������� ��� �������� �� ����������� �������, ���������� ����� � ������, �� ������� ��������� � ������� � contest-�������. ��������� �� ���������� ��� ��������� ���� ���������, ����� ��������� ����������.</p>                        
          <hr />
          <form name="faqform" action="./insertq.php" method="post">
            <!-- �������� page ��� ���������� -->
            <input type="hidden" name="page" value="<?=_data('page')?>" />
            <table class="enter">
              <tr>
                <td class="top">������</td>
                <td>
                  <textarea name="question"
                    wrap="virtual"
                    cols="40"
                    rows="10"><?=_data('question')?></textarea>
              </tr>
              <tr>
                <td>&nbsp;</td><td  class="c">
                  <input type="submit" name="submit" class="submit" value="��������� ������" />
                </td>
              </tr>
            </table>
          </form>
          <hr />
<?php    
//�������� ���
if (0 == count($faq)):
?>
          <p>��� ��������.</p>
<?php    
else:
    // ���� �� ��������, ��� ���� ���������� �������� ��������
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
            �� ���� ������ ���� ��� ������.
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
            ::<a href="./editanswer.php?faqid=<?=$f->faqid?>">������������� �����</a>
            &nbsp;
            ::<a href="./deletefaq.php?faqid=<?=$f->faqid?>">������� ������</a>
          </p>
<?php
          endif;
?>
          <hr />
<?php        
    endwhile; //����� ����� �� ��������
      
      //���������� ������ �������

      if (_data('pagecount') > 0):
?>
          <p class="c">
            ��������:&nbsp;
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
          endif; //����� ������ ������ �������� � ����� �� �������
?>
          </p>
<?php
      endif; //����� ������ ������� �������          
    endif; //����� ��������� ���������� ��������
?>
<?php } ?>
