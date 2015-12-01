<?php function authorize($data = null) { ?>
<?php global $authorized; ?>
<?php before(); ?>
    <div id="xicl-error" style="">
        <table id="xicl-error-content"><tr><td>
            <?php login(); // показываем форму входа/выхода ?>
            <?php if (_has('message')): ?>
            <p class="message"><?=_data('message')?></p>
            <?php endif; ?>
        </td></tr></table>
        <a href="./" id="xicl-error-home" title="на главную">&nbsp;</a>
    </div>
<?php after(); ?>
<?php die(); // прекратить дальнейшую работу ?>
<?php } ?>
