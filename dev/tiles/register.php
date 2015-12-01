<?php function content($data) { ?>
<h3>Регистрация</h3>
<?php if (_has('message')): ?>
<p class="message"><?=_data('message')?></p>
<hr />
<?php endif; ?>
<form action="./register.php" name="frmRegister" method="post">
<table class="enter">
    <tr><td>Логин (*)</td>
    <td><input type=text maxlen=20 size=20 name="login" value="<?=_data('login')?>" /></td><td>&nbsp;</td></tr>
    <tr><td>Пароль (*)</td>
    <td><input type=password maxlen=20 size=20 name="newpass" value="" /></td><td>&nbsp;</td></tr>
    <tr><td>Повтор пароля (*)</td>
    <td><input type=password maxlen=20 size=20 name="passrep" value="" /></td><td>&nbsp;</td></tr>
    <tr><td>Nickname (*)</td>
    <td><input type=text maxlen=30 size=20 name="nickname" value="<?=_data('nickname')?>" /></td><td>&nbsp;</td></tr>
    <tr><td align=center>&nbsp;</td>
    <td class="c"><input type="submit" class="submit" name="regbtn" value="зарегистрироваться" /></td><td>&nbsp;</td></tr>
</table>
</form>
<?php } ?>
