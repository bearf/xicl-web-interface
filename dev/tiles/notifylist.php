<?php function content($data) { ?>
<?php global $is_admin; ?>
<?php if (_has('message')): ?>
<p class="message"><?=_data('message')?></p>
<?php endif; ?>
<?php if (1 == $is_admin): // проверяем на администратора и показываем форму уведомлений ?>
<form name="addnotify" action="./addnotify.php" method="post">
<!-- параметр page уже установлен -->
<input type="hidden" name="page" value="<?=_data('page')?>" />
<table class="enter">
    <tr><td>пользователю</td>
        <td><select name="touser">
            <option value="-1"<?=!_has('touser') || _has('touser') && -1 == _data('touser') ? ' selected="selected"' : ''?>>-- всем --</option>
            <?php $users = _data('users'); ?>
            <?php while(list($key, $f) = each($users)): ?>
                <option value="<?=$f->userid?>"<?=_has('touser') && $f->userid == _data('touser') ? ' selected="selected"' : ''?>><?=$f->nickname?></option>
            <?php endwhile; ?>
        </select></td></tr>
    <tr><td>команде</td>
        <td><select name="toteam">
            <option value="-1"<?=!_has('toteam') || _has('toteam') && -1 == _data('toteam') ? ' selected="selected"' : ''?>>-- всем --</option>
            <?php $teams = _data('teams'); ?>
            <?php while(list($key, $f) = each($teams)): ?>
                <option value="<?=$f->teamid?>"<?=_has('toteam') && $f->teamid == _data('toteam') ? ' selected="selected"' : ''?>><?=$f->teamname?></option>
            <?php endwhile; ?>
        </select></td></tr>
    <tr><td>заголовок</td>
        <td><input type="text" name="header" value="<?=_has('header') ? _data('header') : ''?>" /></td></tr>
    <tr><td>текст</td>
        <td><textarea name="notify" wrap="virtual" cols="40" rows="10"><?=_has('notify') ? _data('notify') : ''?></textarea></td></tr>
    <tr><td>&nbsp;</td>
        <td class="c"><input type="submit" name="submit" class="submit" value="отправить уведомление" /></td></tr>
</table>
</form>
<?php endif; // конец проверки на администратора и показ формы уведомлений ?>
<?php $notifies = _data('notifies'); ?>
<?php if (0 == count($notifies)): // уведомлений нет ?>
<p class="message">Нет уведомлений.</p>
<?php else: ?>
    <?php while (list($key, $f) = each($notifies)): // цикл по уведомлениям ?>
        <div class="<?=$f->read > 0 ? 'message-read' : 'message'?>">
        <h4>
            <?=$f->header?>&nbsp;<span style="font-weight:normal;">|&nbsp;<?=$f->date?><span>
            <?php if (!$f->read): ?>
                <span style="float:right;">::<a href="./closenotify.php?notifyid=<?=$f->notifyid?>&amp;page=<?=_data('page')?>" title="закрыть и отметить как прочитанное">закрыть</a>
            <?php endif; ?>
        </h4>
        <?=$f->notify?>
        </div>
    <?php endwhile; //конец цикла по уведомлениям ?>
    <?php if (_data('pagecount') >= 2): //отображаем список страниц ?>
        <p class="c">
        страницы:&nbsp;
        <?php for ($i=1; $i<=_data('pagecount'); $i++): ?>
            <?php if (_data('page')==$i): ?>
                <strong><?=$i?></strong>
            <?php else: ?>
                <a href="./notifylist.php?page=<?=$i?>"><?=$i?></a>
            <?php endif; //конец вывода номера страницы ?>
        <?php endfor; // конец цикла по номерам ?>
        </p>
    <?php endif; //конец вывода номеров страниц ?>
<?php endif; //конец обработки отсутствия уведомлений ?>
<?php } ?>
