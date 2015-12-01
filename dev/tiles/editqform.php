<?php function content($data) { ?>
<?php global $messages ?>
          <h3>�������������� ������� �� ������: <?=_data('taskName')?></h3>
          <hr />
<?php
    if (_has('code')): // todo: �� ����, ��� ���
?>
          <p class="message"><?=$messages[_data('code')]?></p>
          <hr />
<?php
    endif;
?>          
          <form name="questionForm" id="questionForm" action="./editq.php" method="post">
            <input type="hidden" name="taskId" value="<?=_data('taskId')?>" />
            <input type="hidden" name="questionId" value="<?=_data('questionId')?>" />
            <table class="enter">
              <tr>
                <td class="remark">������</td>
                <td><?=_data('question')?></td>
              </tr>
              <tr>
                <td class="remark">�����</td>
                <td>
                  <input 
                    type="checkbox" 
                    id="yes"
                    class="checkbox" 
                    name="yes" 
                    <?=_data('yes_checkbox_value')?> 
                    onclick="document.getElementById('no').checked=false;document.getElementById('nocomment').checked=false;"
                    /> ��&nbsp;&nbsp;
                  <input 
                    type="checkbox" 
                    id="no"
                    class="checkbox" 
                    name="no" 
                    <?=_data('no_checkbox_value')?> 
                    onclick="document.getElementById('yes').checked=false;document.getElementById('nocomment').checked=false;"
                    /> ���&nbsp;&nbsp;
                  <input 
                    type="checkbox" 
                    id="nocomment"
                    class="checkbox" 
                    name="nocomment" 
                    <?=_data('nocomment_checkbox_value')?> 
                    onclick="document.getElementById('yes').checked=false;document.getElementById('no').checked=false;"
                    /> ��� ������������&nbsp;&nbsp;
                </td>
              </tr>
              <tr>
                <td class="remark">�����������</td>
                <td>
                  <textarea name="comment" rows="7" cols="30"><?=stripslashes(_data('comment'))?></textarea>
                </td>
              </tr>
              <tr><td>&nbsp;</td>
              <td class="c"><input type="submit" class="submit" name="submit" value="����������� ���������" /></td></tr>
            </table>          
            
          </form>
<?php } ?>
