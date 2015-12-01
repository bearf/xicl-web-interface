<?php function content($data) { ?>
<?php global $is_admin; ?>
<?php if (_has('message')): ?>
<p class="message"><?=_data('message')?></p>
<?php endif; ?>
<?php if (1 == $is_admin): // ��������� �� �������������� � ���������� ����� ����������� ?>
<form name="addnotify" action="./addnotify.php" method="post">
<!-- �������� page ��� ���������� -->
<input type="hidden" name="page" value="<?=_data('page')?>" />
<table class="enter">
    <tr><td>������������</td>
        <td><select name="touser">
            <option value="-1"<?=!_has('touser') || _has('touser') && -1 == _data('touser') ? ' selected="selected"' : ''?>>-- ���� --</option>
            <?php $users = _data('users'); ?>
            <?php while(list($key, $f) = each($users)): ?>
                <option value="<?=$f->userid?>"<?=_has('touser') && $f->userid == _data('touser') ? ' selected="selected"' : ''?>><?=$f->nickname?></option>
            <?php endwhile; ?>
        </select></td></tr>
    <tr><td>�������</td>
        <td><select name="toteam">
            <option value="-1"<?=!_has('toteam') || _has('toteam') && -1 == _data('toteam') ? ' selected="selected"' : ''?>>-- ���� --</option>
            <?php $teams = _data('teams'); ?>
            <?php while(list($key, $f) = each($teams)): ?>
                <option value="<?=$f->teamid?>"<?=_has('toteam') && $f->teamid == _data('toteam') ? ' selected="selected"' : ''?>><?=$f->teamname?></option>
            <?php endwhile; ?>
        </select></td></tr>
    <tr><td>���������</td>
        <td><input type="text" name="header" value="<?=_has('header') ? _data('header') : ''?>" /></td></tr>
    <tr><td>�����</td>
        <td><textarea name="notify" wrap="virtual" cols="40" rows="10"><?=_has('notify') ? _data('notify') : ''?></textarea></td></tr>
    <tr><td>&nbsp;</td>
        <td class="c"><input type="submit" name="submit" class="submit" value="��������� �����������" /></td></tr>
</table>
</form>
<?php endif; // ����� �������� �� �������������� � ����� ����� ����������� ?>
<?php $notifies = _data('notifies'); ?>
<?php if (0 == count($notifies)): // ����������� ��� ?>
<p class="message">��� �����������.</p>
<?php else: ?>
    <?php while (list($key, $f) = each($notifies)): // ���� �� ������������ ?>
        <div class="<?=$f->read > 0 ? 'message-read' : 'message'?>">
        <h4>
            <?=$f->header?>&nbsp;<span style="font-weight:normal;">|&nbsp;<?=$f->date?><span>
            <?php if (!$f->read): ?>
                <span style="float:right;">::<a href="./closenotify.php?notifyid=<?=$f->notifyid?>&amp;page=<?=_data('page')?>" title="������� � �������� ��� �����������">�������</a>
            <?php endif; ?>
        </h4>
        <?=$f->notify?>
        </div>
    <?php endwhile; //����� ����� �� ������������ ?>
    <?php if (_data('pagecount') >= 2): //���������� ������ ������� ?>
        <p class="c">
        ��������:&nbsp;
        <?php for ($i=1; $i<=_data('pagecount'); $i++): ?>
            <?php if (_data('page')==$i): ?>
                <strong><?=$i?></strong>
            <?php else: ?>
                <a href="./notifylist.php?page=<?=$i?>"><?=$i?></a>
            <?php endif; //����� ������ ������ �������� ?>
        <?php endfor; // ����� ����� �� ������� ?>
        </p>
    <?php endif; //����� ������ ������� ������� ?>
<?php endif; //����� ��������� ���������� ����������� ?>
<?php } ?>
