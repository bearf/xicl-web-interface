<?php
function content($data) {
$header = _has('header') ? _data('header') : '���������';
$message = _has('message') ? _data('message') : '';
?>
<h3><?=$header?></h3>
<p class="message"><?=$message?></p>
<?php
}
?>
