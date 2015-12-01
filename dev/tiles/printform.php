<?php function content($data) { ?>
<?php global $messages; ?>
<?php $tasks = _data('tasks'); ?>
          <h3>������ �������</h3>
          <hr />
<?php
//�������� �� ��������� �� addprint.php
if (_has('outcode')):
?>          
          <p class="message"><?=$messages[_data('outcode')]?></p>
          <hr />
<?php
endif; //����� �������� �� ������� ��������� �� addprint.php
//�������� �� ���������
if (_has('code')): // todo: WTF?
?>          
          <p class="message"><?=$messages[_data('code')]?></p>
          <hr />
<?php
endif; //����� �������� �� ������� ���������
?>
          <form name="printForm" id="printForm" action="./addprint.php" method="post">
            <table class="enter">
              <tr>
                <td>������</td>
                <td>
                  <select name="problemId">
<?php
//���� ����� ��� - ��������� ��������� ������
if (0 == count($tasks)):
    $empty = true;
?>                  
                    <option value="-" selected="selected">----------------</option>
<?php
//����� - ���� �� ����������� �������
else:
    $empty = false;
    $first = true;
    while (list($key, $instance) = each($tasks)):
        $selected = _has('problemId') && _data('problemId') == $instance->ProblemID || !_has('problemId') && $first == true ? ' selected="selected" ' : '';
?>
                    <option value="<?=$instance->ProblemID?>" <?=$selected?>><?=$instance->ProblemID?>. <?=$instance->Name?></option>
<?php    
        $first = false;
    endwhile; //����� ����� ������
endif; //����� �������� �� ������������� ����������� ������
?>                    
                  </select>
                </td>
              </tr>
              <tr>
                <td class="top">�������� ���</td>
                <td>
                  <textarea name="source" rows="10" cols="30"><?=get_magic_quotes_gpc() ? stripslashes(_data('source')) : _data('source')?></textarea>
                </td>
              </tr>
              <tr>
                <td colspan="2"><hr /></td>
              </tr>
              <tr>
                <td colspan="2" class="c">
<?php
//���� ����� ��� - ����� ����������� ������
$disabled = $empty ? ' disabled="disabled" ' : '';
?>            
                  <input type="submit" class="submit" name="submit" value="�������" <?=$disabled?> />
                </td>
              </tr>
            </table>          
          </form>
<?php } ?>
