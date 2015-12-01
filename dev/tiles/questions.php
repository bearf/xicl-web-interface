<?php function content($data) { ?>
<?php global $authorized; ?>
<?php global $is_admin; ?>
<?php $questions = _data('questions'); ?>
<h3>������� �� ������: <?=_data('taskName')?></h3>
<hr />
<p>������ ������ ���� ��������� � ����� �����, ����� �� ���� ����� ���� �������� "��" ��� "���". �������, �� ������� ������ �������� �������� �������, � ����� �������, ������ �� ������� ���������� � �������, ����� �������� ����� "��� ������������".</p>
<p>������� (� ������), ����������� ����� ��������, ����� ������ ������������� ���, ����� ���� ������, ����� ������ ������������� ����� ��� ��������� ������� ������, ��� ���� �� �������� ������� ���� ������ � ������, ��� ������� ���� ����� �������� ������. � ���� ������ ������ ���������� ��� "�����" � ���������� ������� ���� ��������.</p>
<hr />
<?php if ($authorized == 1): ?>
<p>::<a href="./addqform.php?taskId=<?=_data('taskId')?>">�������� ����� ������</a>&nbsp;</p>
<?php else: ?>
<p class="message">��������� ����� ������� ����� ������ ������������������ ������������.</p>
<?php endif; ?>    
<hr />
<?php if (0 == count($questions)): //������� ���������, ��� �������� ��� ?>
<p class="message">��� �������� ��� �����������.</p>
<?php endif; ?>
<?php
    //���� �� ������� � ������� ��������
    while (list($key, $instance) = each($questions)):
        //������� ����� �� ������ ����� ������
        $row_span = 0;
        if ($instance->question) { $row_span++; }
        if ($instance->result > 0) { $row_span++; }
        if ($instance->comment) { $row_span++; }
      
        //����������, ����� �� ��������� ����� ��������
        $yes_checkbox_value = $instance->result == 1 ? ' checked="checked ' : '';
        $no_checkbox_value = $instance->result == 2 ? ' checked="checked ' : '';
        $nocomment_checkbox_value = $instance->result == 3 ? ' checked="checked" ' : '';
        $class = '0' === $instance->result && ('' === $instance->comment || null == $instance->comment) && $is_admin == 1 ? ' new' : '';
        
        //������� ����, ��� ������ ���� ����������
        $new_line = false;
?>
          <table class="forum<?=$class?>">
<?php
        //� ������ ��������� ����������� ���������� ������ ����������
        if (1 == $is_admin):
?>
            <tr>
              <td colspan="2" class="actions">
                <sup>
                  ::<a href="./editqform.php?questionId=<?=$instance->questionId?>">��������</a>
                  &nbsp;
                  ::<a href="./deleteq.php?questionId=<?=$instance->questionId?>">�������</a>
                  &nbsp;
<?php
            //���� ������ �� ��������� - �������� ������� "������� ���������"
            if ($instance->isPublic == 0):
?>
                  ::<a href="./publicq.php?questionId=<?=$instance->questionId?>&public=1">�������� ��� ���������</a>
<?php
            //����� �������� ������� "������� �����������"
            else:
?>
                  ::<a href="./publicq.php?questionId=<?=$instance->questionId?>&public=0">�������� ��� �����������</a>
<?php
            endif; //����� �������� ��������������� �������
?>                  
                </sup>
              </td>
            </tr>
<?php
        endif; //����� ��������� ������ ����������
?>          
            <tr>
              <td rowspan="<?=$row_span?>" class="userdate">
                <?php userlink($instance->userNickName, $instance->userId); ?>
                <sup><?=$instance->dateTime?></sup>
<?php
        if ($instance->isPublic == 1):                
?>
                <sup>���������</sup>
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
                <input type="checkbox" class="checkbox" name="yes" <?=$yes_checkbox_value?> disabled="disabled" /> ��&nbsp;&nbsp;
                <input type="checkbox" class="checkbox" name="no" <?=$no_checkbox_value?> disabled="disabled" /> ���&nbsp;&nbsp;
                <input type="checkbox" class="checkbox" name="nocomment" <?=$nocomment_checkbox_value?> disabled="disabled" /> ��� ������������&nbsp;&nbsp;
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
<?php   if (!$new_line): //��������, ��� ����� ������������� ������� ������?>
            </tr>
<?php   endif; //����� �������� �� ������������� ������� ������ ?>
          </table>
<?php endwhile; //����� ����� �� ������� � ������� �������� ?>          
<?php } ?>
