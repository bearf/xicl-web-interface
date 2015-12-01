<?php function content($data) { ?>
<?php global $messages; ?>
<h3>Вопрос по задаче: <?=_data('taskName')?></h3>
<hr />
<?php if (_has('code')): ?>
<p class="message"><?=$messages[_data('code')]?></p>
<hr />
<?php endif; ?>          
<form name="questionForm" id="questionForm" action="./addq.php" method="post">
<input type="hidden" name="taskId" value="<?=_data('taskId')?>" />
<table class="enter">
    <tr><td class="top">вопрос</td>
    <td><textarea name="question" wrap="virtual"  cols="40" rows="10"><?=stripslashes(_data('question'))?></textarea></tr>
    <tr><td>&nbsp;</td><td class="c">
        <input type="submit" name="submit" class="submit" value="отправить вопрос" />
    </td></tr>
</table>          
</form>
<?php } ?>
