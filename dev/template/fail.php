<?php function fail($message) { ?>
<?php global $messages; ?>
<?php before(); ?>
    <div id="xicl-error" style="">
        <table id="xicl-error-content"><tr><td>
            <p class="message error"><?=is_string($message) ? $message : (is_int($message) ? $messages[$message] : 'unknown param for fail page')?></p>
        </td></tr></table>
        <a href="<?=ServerRoot?>" id="xicl-error-home" title="на главную">&nbsp;</a>
    </div>
<?php after(); ?>
<?php die(); // прекратить дальнейшую работу ?>
<?php } ?>
