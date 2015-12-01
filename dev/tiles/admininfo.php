<?php function content($data) { ?>
<?php $new_questions = _data('new_questions'); ?>
<?php $old_questions = _data('old_questions'); ?>
<?php $print_queue = _data('print_queue'); ?>
          <p>
          ::<a href="#new_questions">����� �������</a>
          &nbsp;::<a href="#old_questions">������ �������</a>
          &nbsp;::<a href="#print_queue">������� ������</a>
          </p>
          <hr />
          <h3 id="new_questions">����� �������</h3>    
<?php    
//������� ���������, ��� �������� ���
if (count($new_questions) == 0):
?>
          <p>��� �������� ��� �����������.</p>
<?php                  
else:
?>
          <table style="width:100%;">
            <tr>
              <th>������������</th>
              <th>������</th>
              <th>������</th>
              <th>��������</th>
            </tr>
<?php
$nth = false;
//���� �� ������� � ������� ��������
while (list($key, $value) = each($new_questions)):
    //����������� ����� ������
    if (!$nth):
?>
            <tr>
<?php
    else:
?>
            <tr class="s">
<?php
    endif; //����� ����������� ����� ������
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
                  ::<a href="./editqform.php?questionId=<?=$value->questionId?>">��������</a>
                  &nbsp;
                  ::<a href="./deleteq.php?questionId=<?=$value->questionId?>">�������</a>
                  &nbsp;
<?php
            //���� ������ �� ��������� - �������� ������� "������� ���������"
            if ($value->isPublic == 0):
?>
                  ::<a href="./publicq.php?questionId=<?=$value->questionId?>&public=1">�������� ��� ���������</a>
<?php
            //����� �������� ������� "������� �����������"
            else:
?>
                  ::<a href="./publicq.php?questionId=<?=$value->questionId?>&public=0">�������� ��� �����������</a>
<?php
            endif; //����� �������� ��������������� �������
?>                  
                </sup>
              </td>
            </tr>
<?php
endwhile; //����� ����� �� ������� � ������� ��������
?>
          </table>
<?php
endif; // ����� �������� �� ������� ��������
?>          
          <hr />
          <h3 id="old_questions">������ �������</h3>    
<?php    
//������� ���������, ��� �������� ���
if (count($old_questions) == 0):
?>
          <p>��� �������� ��� �����������.</p>
<?php                  
else:
?>
          <table style="width:100%;">
            <tr>
              <th>������������</th>
              <th>������</th>
              <th>������</th>
              <th>��������</th>
            </tr>
<?php
    //���� �� ������� � ������� ��������
    $nth = false;
    while (list($key, $value) = each($old_questions)):
        //����������� ����� ������
        if (!$nth):
?>
            <tr>
<?php
        else:
?>
            <tr class="s">
<?php
    endif; //����� ����������� ����� ������
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
                  ::<a href="./editqform.php?questionId=<?=$value->questionId?>">��������</a>
                  &nbsp;
                  ::<a href="./deleteq.php?questionId=<?=$value->questionId?>">�������</a>
                  &nbsp;
<?php
            //���� ������ �� ��������� - �������� ������� "������� ���������"
            if ($value->isPublic == 0):
?>
                  ::<a href="./publicq.php?questionId=<?=$value->questionId?>&public=1">�������� ��� ���������</a>
<?php
            //����� �������� ������� "������� �����������"
            else:
?>
                  ::<a href="./publicq.php?questionId=<?=$value->questionId?>&public=0">�������� ��� �����������</a>
<?php
            endif; //����� �������� ��������������� �������
?>                  
                </sup>
              </td>
            </tr>
<?php
    endwhile; //����� ����� �� ������� � ������� ��������
?>
          </table>
<?php
endif; // ����� �������� �� ������� ��������
?>          
          <hr />
          <h3 id="print_queue">������� ������</h3>
<?php    
//������� ���������, ��� �������� ���
if (count($print_queue) == 0):
?>
          <p>������� �����.</p>
<?php                  
else:
?>
          <table style="width:100%;">
            <tr>
              <th style="width:30%">������������</th>
              <th style="width:30%">������</th>
              <th style="width:5%">������</th>
              <th style="width:45%">��������</th>
            </tr>
<?php
    //���� �� ������� � ������� ������
    $nth = false;
    while (list($key, $value) = each($print_queue)):
      //����������� ������ � ������� ������
      $status = '��������...';
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
        $status = '� �������';
        $status_color = ' style="color:gray" ';
      }
      
      //����������� ����� ������
      if (!$nth):
?>
            <tr>
<?php
      else:
?>
            <tr class="s">
<?php
      endif; //����� ����������� ����� ������
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
                  ::<a href="./deletep.php?printId=<?=$value->printId?>">�������</a>
                  &nbsp;
<?php
      if ((1 == $value->isPrinted) || (3 == $value->isPrinted) || (4 == $value->isPrinted)):
?>                  
                  ::<a href="./clearp.php?printId=<?=$value->printId?>">�������� ������</a>
<?php
      endif;
?>                  
                </sup>
              </td>
            </tr>
<?php
    endwhile; //����� ����� �� ������� � ������� ������
endif; // ����� �������� �� ������� ������� � ������� ������ 
?>
          </table>
<?php } ?>
