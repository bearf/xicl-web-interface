<?php function content($data) { ?>
<h3>���������&nbsp;&nbsp;::<a href="./mail.php">��������</a></h3>
<?php $mail = _data('mail'); ?>
<?php $rowcount = count($mail); ?>
<?php if (0 == $rowcount): ?>
<p class="message">��������� ���</p>
<?php else: ?>
<table class="mail">
<?php $index = 0; ?>
<?php while (list($key, $f) = each($mail)): //������� ������ ?>
    <?php $index++; ?>
<tr class="<?=$index%2==0 ? 's' : ''?> <?=0 == $f->read ? 'unread' : ''?> <?=0 == $f->inbound ? '' : 'inbound'?>">
    <td class="user"><?php userlink($f->nickname, $f->from) ?>
        <?=0 == $f->inbound ? '&larr;' : '&rarr;'?>
        <br /><?=$f->date?></td>
    <td class="mail"><div class="mail-container">
        <a class="mail-header" href="javascript:void(0);" onclick="toggleMail(this.parentNode<?=1 == $f->inbound ? ','.$f->messageid : ''?>);"><?=substr($f->text, 0, 50)?><?=strlen($f->text)>50 ? '...' : ''?></a>
        <div class="mail-text" onclick="toggleMail(this.parentNode);"><?=$f->text?></div>
    </div></td></tr>
<?php endwhile; //����� ������� �� ������� ?>
</table>
<?php if (_data('pagecount') >= 2): ?>
<p class="c">��������:&nbsp;
<?php for ($i=1; $i<=_data('pagecount'); $i++): ?>
    <?php if (_data('page')==$i): ?>
    <strong><?=$i?></strong>
    <?php else: ?>
    <a href="./mail.php?page=<?=$i?>"><?=$i?></a>
    <?php endif; ?>
<?php endfor; // ����� ����� �� ������� ������� ?>
</p>
<?php endif; // ����� �������� �� ����� ������� ?>
<?php endif; // ����� ���� �� ������� ������� ?>
<?php } ?>

