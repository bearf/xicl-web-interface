<?php function authorize($data = null) { ?>
<?php global $authorized; ?>
<?php before(); ?>
    <div id="xicl-error" style="">
        <table id="xicl-error-content"><tr><td>
            <?php login(); // ���������� ����� �����/������ ?>
            <?php if (_has('message')): ?>
            <p class="message"><?=_data('message')?></p>
            <?php endif; ?>
        </td></tr></table>
        <a href="./" id="xicl-error-home" title="�� �������">&nbsp;</a>
    </div>
<?php after(); ?>
<?php die(); // ���������� ���������� ������ ?>
<?php } ?>
