<?php function content($data) { ?>
<?php global $authorized; ?>
<?php $problem = _data('problem'); ?>
<?php $instance = _data('instance'); ?>
<?php $contest = _data('contest'); ?>
          <h3><?=$problem?>. <?=$instance->Name?></h3>
          <p class="i">
            ������� ����: <?=$instance->Input=='' ? '����������� ����� �����' : $instance->Input?><br />
            �������� ����: <?=$instance->Output=='' ? '����������� ����� ������' : $instance->Output?><br />
            ����������� �������: <?=$instance->TimeLimit==='0' ? '-' : $instance->TimeLimit?> ��<br />
            ����������� ������: <?=$instance->MemoryLimit==='0' ? '-' : $instance->MemoryLimit?> ��<br />
          </p>
          <hr />
          <p class="links">::<a href="submit.php?problem=<?=$instance->ProblemID?>&contest=<?=$contest?>">����� �������</a>
          &nbsp;
          ::<a href="./questions.php?taskId=<?=$instance->TaskID?>">������� �� ������</a>
<?php
if (1 == $authorized):
?>
            &nbsp;::<a href="./addqform.php?taskId=<?=$instance->TaskID?>">������ ������</a>
<?php
endif;
?>          
          &nbsp;::<a href="./problemset.php?contest=<?=$instance->ContestID?>&amp;volume=<?=$instance->VolumeID?>">��������� � ������</a>
          </p>
          <p>
            <?=$instance->Text?>
          </p>
          <h4>������ ������� ������</h4>
          <p><?=$instance->FormatIn?></p>
          <h4>������ �������� ������</h4>
          <p><?=$instance->FormatOut?></p>
          <h4>������(�) ������� ������</h4>
          <p class="code"><?=$instance->SampleIn?></p>
          <h4>������(�) �������� ������</h4>
          <p class="code"><?=$instance->SampleOut?></p>
          <hr />
          <p>
            <?=$instance->Author ? '�����: '.$instance->Author.'<br />' : ''?>
            <?=$instance->Source ? '��������: '.$instance->Source.'<br />' : ''?>
          </p>
<?php } ?>
