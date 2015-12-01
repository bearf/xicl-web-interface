<?php function content($data) { ?>
<h3>Редактирование ответа</h3>
<hr />
<?php if (_has('message')): ?>
<p class="message"><?=_data('message')?></p>
<hr />
<?php endif; ?>
<form name="faqform" action="./editanswer.php">
<!-- параметр faqid уже установлен -->
<input type="hidden" name="faqid" value="<?=_data('faqid')?>" />
<input type="hidden" name="question" value="<?=_data('question')?>" />
<table class="enter">
    <tr><td class="top">вопрос:</td>
    <td><?=_data('question')?></td></tr>
    <tr><td class="top">ответ:</td>
    <td><textarea name="answer" wrap="virtual" cols="40" rows="10"><?=stripslashes(_data('answer'))?></textarea></td></tr>
    <tr><td>&nbsp;</td>
    <td class="c"><input type="submit" name="submit" class="submit" value="принять ответ" /></td></tr>
</table>
</form>
<?php } ?>
