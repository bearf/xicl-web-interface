<?php function content($data) { ?>
<?php global $authorized; ?>
<?php $problem = _data('problem'); ?>
<?php $instance = _data('instance'); ?>
<?php $contest = _data('contest'); ?>
          <h3><?=$problem?>. <?=$instance->Name?></h3>
          <p class="i">
            Входной файл: <?=$instance->Input=='' ? 'стандартный поток ввода' : $instance->Input?><br />
            Выходной файл: <?=$instance->Output=='' ? 'стандартный поток вывода' : $instance->Output?><br />
            Ограничение времени: <?=$instance->TimeLimit==='0' ? '-' : $instance->TimeLimit?> мс<br />
            Ограничение памяти: <?=$instance->MemoryLimit==='0' ? '-' : $instance->MemoryLimit?> Кб<br />
          </p>
          <hr />
          <p class="links">::<a href="submit.php?problem=<?=$instance->ProblemID?>&contest=<?=$contest?>">сдать решение</a>
          &nbsp;
          ::<a href="./questions.php?taskId=<?=$instance->TaskID?>">вопросы по задаче</a>
<?php
if (1 == $authorized):
?>
            &nbsp;::<a href="./addqform.php?taskId=<?=$instance->TaskID?>">задать вопрос</a>
<?php
endif;
?>          
          &nbsp;::<a href="./problemset.php?contest=<?=$instance->ContestID?>&amp;volume=<?=$instance->VolumeID?>">вернуться к списку</a>
          </p>
          <p>
            <?=$instance->Text?>
          </p>
          <h4>Формат входных данных</h4>
          <p><?=$instance->FormatIn?></p>
          <h4>Формат выходных данных</h4>
          <p><?=$instance->FormatOut?></p>
          <h4>Пример(ы) входных данных</h4>
          <p class="code"><?=$instance->SampleIn?></p>
          <h4>Пример(ы) выходных данных</h4>
          <p class="code"><?=$instance->SampleOut?></p>
          <hr />
          <p>
            <?=$instance->Author ? 'Автор: '.$instance->Author.'<br />' : ''?>
            <?=$instance->Source ? 'Источник: '.$instance->Source.'<br />' : ''?>
          </p>
<?php } ?>
