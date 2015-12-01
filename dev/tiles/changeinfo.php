<?php function content($data) { ?>
    <?php global $curteamid; ?>
    <?php if (_has('message')): ?>
        <p class="message"><?=_data('message')?></p>
    <?php endif; ?>
    <h3>Изменение данных</h3>
    <hr />
    <p>Введите необходимые данные. Поля, отмеченные символом (*) являются
    обязательными для ввода.</p>
    <hr />
    <form action="./changeinfo.php" name="frmChangeInfo" method="post">
        <table class="enter">
            <tr><td>&nbsp;</td>
            <td><input id="changepassword" type="checkbox" class="checkbox" <?='0' != _data('changepassword') ? 'checked="checked"' : ''?> name="changepassword" onclick="document.getElementById('newpass').disabled=!this.checked;document.getElementById('passrep').disabled=!this.checked;" />
                &nbsp;Сменить пароль</td><td>&nbsp;</td></tr>
            <tr><td>Новый пароль</td>
            <td><input type="password" maxlen="20" size="20" id="newpass" name="newpass" <?='0' == _data('changepassword') ? 'disabled="disabled"' : ''?> value="" /></td><td>&nbsp;</td></tr>
            <tr><td>Повтор пароля</td>
            <td><input type="password" maxlen="20" size="20" id="passrep" name="passrep" <?='0' == _data('changepassword') ? 'disabled="disabled"' : ''?> value="" /></td><td>&nbsp;</td></tr>
            <tr><td>Nickname (*)</td>
            <td><input type="text" maxlen="30" size="20" name="nickname" value="<?=_data('nickname')?>" /></td><td>&nbsp;</td></tr>

            <tr><td>Место учебы</td>
            <td><input type="text" maxlen="50" size="20" name="studyplace" value="<?=_data('studyplace')?>" /></td><td>&nbsp;</td></tr>
            <tr><td>Класс/курс</td>
            <td><input type="text" maxlen="2" size="20" name="clss" value="<?=_data('clss')?>"></td><td>&nbsp;</td></tr>
            <tr><td>E-mail</td>
            <td><input type="text" maxlen="40" size="20" name="email" value="<?=_data('email')?>" /></td><td>&nbsp;</td></tr>
            <tr><td></td>
            <td><input type="checkbox" class="checkbox" <?='0' != _data('allowpublish') ? 'checked="checked"' : ''?> name="allowpublish" />
                &nbsp;Показывать e-mail</td><td>&nbsp;</td></tr>
            <?php if (_data('teamid')): ?>
                <tr><td>Команда</td><td>
                    <?php if ($curteamid == _data('teamid') && (_permission_allow_update_team_info || 1 == $is_admin)): ?>
                        <a href="./team/update/"><?php echo _data('teamname');?></a>
                    <?php elseif (_permission_allow_view_team_info || 1 == $is_admin): ?>
                        <a href="./team/view/?teamid=<?php echo _data('teamid'); ?>"><?php echo _data('teamname');?></a>
                    <?php else: ?>
                        <?php echo _data('teamname');?>
                    <?php endif; ?>
                </td></tr>
            <?php endif; ?>
            <tr><td style="vertical-align:top">Информация</td>
            <td><textarea name="info" wrap="virtual" rows="5" cols="20"><?=stripslashes(_data('info'))?></textarea></td><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td>
            <td style="text-align:center"><input type="submit" class="submit" name="changebtn" value="изменить" /></td><td>&nbsp;</td></tr>
        </table>
    </form>

    <?php if (!_has('persInfo')): // нет личных данных ?>
        <h3>Создание персональных данных</h3>
        <hr />
        <p>В настоящее время вы не ввели дополнительную информацию, которая поможет нам при бронировании места в гостинице или при оформлении призов. В соответствии с <a href="http://www.rg.ru/2006/07/29/personaljnye-dannye-dok.html">Федеральным законом N 152-ФЗ</a> эта информация относится к персональным данным и нам требуется разрешение для ее хранения. Ни при каких условиях эта информация не станет доступна третьим лицам. Также, она может быть удалена по первому вашему требованию.</p>

        <form action="./persinfo/create/" name="frmCreatePersInfo" method="post">
            <table class="enter">
                <tr><td>&nbsp;</td>
                    <td>
                        <input id="sure" type="checkbox" class="checkbox" name="changepassword" onclick="document.getElementById('btnCreatePersInfo').disabled=!this.checked;" />
                        я даю согласие на обработку персональных данных
                    </td></tr>
                <tr><td>&nbsp;</td>
                    <td style="text-align:center">
                        <input type="submit" class="submit" id="btnCreatePersInfo" name="btnCreatePersInfo" disabled="disabled" value="подтвердить" />
                    </td><td>&nbsp;</td></tr>
            </table>
        </form>
    <?php endif; ?>

    <?php if (_has('persInfo')): // есть личные данные - update ?>
        <h3>Изменение персональных данных</h3>
        <hr />
        <p>Заполните необходимые данные. Поля, отмеченные символом (*) являются обязательными для ввода.</p>
        <form action="./persinfo/update/" name="frmUpdatePersInfo" method="POST">
            <?php $personalInfo = _data('persInfo'); ?>
            <input type="hidden" name="persinfoid" value="<?=$personalInfo->getId()?>" />
            <input type="hidden" name="persinfouserId" value="<?=$personalInfo->getUserId()?>" />
            <table class="enter">

                <tr><td>Фамилия (*)</td>
                    <td><input type="text" maxlen="50" size="50" name="persinfosurname" value="<?=$personalInfo->getSurname()?>" /></td>
                    <td id="persinfosurname-messages">&nbsp;</td></tr>
                <!--<validator target="persinfosurname" required=" - обязательно!" maxlength=" - 50 символов max;50" change="true" place="persinfosurname-messages" />-->
                <tr><td>Имя (*)</td>
                    <td><input type="text" maxlen="50" size="50" name="persinfoname" value="<?=$personalInfo->getName()?>" /></td>
                    <td id="persinfoname-messages">&nbsp;</td></tr>
                <!--<validator target="persinfoname" required=" - обязательно!" maxlength=" - 50 символов max;50" change="true" place="persinfoname-messages" />-->
                <tr><td>Отчество (*)</td>
                    <td><input type="text" maxlen="50" size="50" name="persinfopatrName" value="<?=$personalInfo->getPatrName()?>" /></td>
                    <td id="persinfopatrName-messages">&nbsp;</td></tr>
                <!--<validator target="persinfopatrName" required=" - обязательно!" maxlength=" - 50 символов max;50" change="true" place="persinfopatrName-messages" />-->
                <tr><td>Дата рождения (*)</td>
                    <td><input type="text" maxlen="50" size="50" name="persinfobirthDate" value="<?=$personalInfo->getBirthDate()?>" /></td>
                    <td id="persinfobirthDate-messages">&nbsp;</td></tr>
                <!--<validator target="persinfobirthDate" required=" - обязательно!" regex=" - ДД.ММ.ГГГГ;^[0-3][0-9]\.[0-1][0-9]\.[1-9][0-9][0-9][0-9]$" change="true" place="persinfobirthDate-messages" />-->

                <tr><td colspan="3"><hr /></td></tr>

                <tr><td>Серия и номер паспорта (*)</td>
                    <td><input type="text" maxlen="50" size="50" name="persinfopassportNo" value="<?=$personalInfo->getPassportNo()?>" /></td>
                    <td id="persinfopassportNo-messages">&nbsp;</td></tr>
                <!--<validator target="persinfopassportNo" required=" - обязательно!" maxlength=" - 10 цифр;10" regex=" - например, 9299444555 или AB3334444;^([A-Z]|[a-z]){2}([0-9]){7}$|^([0-9]){10}$" change="true" place="persinfopassportNo-messages" />-->
                <tr><td>Когда выдан паспорт (*)</td>
                    <td><input type="text" maxlen="10" size="50" name="persinfopassportDate" value="<?=$personalInfo->getPassportDate()?>" /></td>
                    <td id="persinfopassportDate-messages">&nbsp;</td></tr>
                <!--<validator target="persinfopassportDate" required=" - обязательно!" regex=" - ДД.ММ.ГГГГ;^[0-3][0-9]\.[0-1][0-9]\.[1-9][0-9][0-9][0-9]$" change="true" place="persinfopassportDate-messages" />-->
                <tr><td>Кем выдан паспорт (*)</td>
                    <td><input type="text" maxlen="100" size="50" name="persinfopassportIssue" value="<?=$personalInfo->getPassportIssue()?>" /></td>
                    <td id="persinfopassportIssue-messages">&nbsp;</td></tr>
                <!--<validator target="persinfopassportIssue" required=" - обязательно!" maxlength=" - 100 символов max;100" change="true" place="persinfopassportIssue-messages" />-->
                <tr><td>ИНН</td>
                    <td><input type="text" maxlen="50" size="50" name="persinfoPTPN" value="<?=$personalInfo->getPTPN()?>" /></td>
                    <td id="persinfoPTPN-messages">&nbsp;</td></tr>
                <!--<validator target="persinfoPTPN" regex=" - 12 цифр;^$|^([0-9]){12}$" maxlength=" - 12 цифр;12" change="true" place="persinfoPTPN-messages" />-->

                <tr><td colspan="3"><hr /></td></tr>

                <tr><td>Контактный телефон (*)</td>
                    <td><input type="text" maxlen="50" size="50" name="persinfophone" value="<?=$personalInfo->getPhone()?>" /></td>
                    <td id="persinfophone-messages">&nbsp;</td></tr>
                <!--<validator target="persinfophone" required=" - обязательно!" regex=" - (###)#######;^\([0-9][0-9][0-9]\)[0-9][0-9][0-9][0-9][0-9][0-9][0-9]$" change="true" place="persinfophone-messages" />-->

                <tr><td colspan="3"><hr /></td></tr>

                <tr><td>Регион (*)</td>
                    <td><input type="text" maxlen="50" size="50" name="persinforegion" value="<?=$personalInfo->getRegion()?>" /></td>
                    <td id="persinforegion-messages">&nbsp;</td></tr>
                <!--<validator target="persinforegion" required=" - обязательно!" maxlength=" - 50 символов max;50" change="true" place="persinforegion-messages" />-->
                <tr><td>Город (*)</td>
                    <td><input type="text" maxlen="50" size="50" name="persinfocity" value="<?=$personalInfo->getCity()?>" /></td>
                    <td id="persinfocity-messages">&nbsp;</td></tr>
                <!--<validator target="persinfocity" required=" - обязательно!" maxlength=" - 50 символов max;50" change="true" place="persinfocity-messages" />-->
                <tr><td>Почтовый индекс (*)</td>
                    <td><input type="text" maxlen="50" size="50" name="persinfopostIndex" value="<?=$personalInfo->getPostIndex()?>" /></td>
                    <td id="persinfopostIndex-messages">&nbsp;</td></tr>
                <!--<validator target="persinfopostIndex" required=" - обязательно!" regex=" - 6 цифр;^[0-9][0-9][0-9][0-9][0-9][0-9]$" change="true" place="persinfopostIndex-messages" />-->
                <tr><td>Адрес</td>
                    <td><input type="text" maxlen="50" size="50" name="persinfoaddress" value="<?=$personalInfo->getAddress()?>" /></td>
                    <td id="persinfoaddress-messages">&nbsp;</td></tr>
                <!--<validator target="persinfoaddress" required=" - обязательно!" maxlength=" - 100 символов max;100" change="true" place="persinfoaddress-messages" />-->

                <tr><td>&nbsp;</td>
                    <td style="text-align:center">
                        <input type="submit" class="submit" id="btnUpdatePersInfo" name="btnUpdatePersInfo" value="изменить" onclick="return KIR.validator('frmUpdatePersInfo').validate();" />
                    </td><td>&nbsp;</td></tr>

            </table>
        </form>
    <?php endif; ?>

    <?php if (_has('persInfo')): // есть личные данные - delete ?>
        <h3>Удаление персональных данных</h3>
        <hr />
        <p>В соответствии с <a href="http://www.rg.ru/2006/07/29/personaljnye-dannye-dok.html">Федеральным законом N 152-ФЗ</a> вы можете удалить свои персональные данные.</p>
        <form action="./persinfo/delete/" name="frmDeletePersInfo" method="POST">
            <input type="hidden" name="persinfoid" value="<?=_data('persInfo')->getId()?>" />
            <input type="hidden" name="persinfouserId" value="<?=_data('persInfo')->getUserId()?>" />
            <table class="enter">
                <tr><td>&nbsp;</td>
                    <td style="text-align:center">
                        <input type="submit" class="submit" id="btnDeletePersInfo" name="btnDeletePersInfo" value="удалить" />
                    </td><td>&nbsp;</td></tr>
            </table>
        </form>
    <?php endif; ?>

<?php } ?>
