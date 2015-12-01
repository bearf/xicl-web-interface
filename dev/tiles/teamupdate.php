<?php function content($data) {
    global $curuserid; ?>

    <?php if (_has('message')): ?>
        <p class="message"><?=_data('message')?></p>
    <?php endif; ?>
    <h3>��������� ������ �������</h3>
    <hr />
    <p>��� ������ �������� (������������ �������, ������������ ���������, ���������� ������), �� ���������� ��� ��������� ��������� ������.</p>
    <p>��� ��������� (�������, ������������) ����� ������ ������ ��� �������, � ����� ������� ������������������� ������������ �� ����������� ������. ��� ����� ������ ������� ���� � ����. </p>
    <p>�����������, ����� ��� ������� ���� �������� ��� ������ �� ������ ������������������ �������������. � ���� ������ � ��� ������� ���������� ������� �������������� ������ ������.</p>
    <hr />
    <form action="./team/update/" name="frmTeamUpdate" method="post">
        <input type="hidden" name="teamid" value="<?php echo _data('team')->getId(); ?>" />
        <table class="enter">
            <tr><td>�������� (*)</td>
                <td><input type="text" maxlen="60" size="20" name="teamname" value="<?=_data('team')->getName()?>" /></td>
                <td id="teamname-messages">&nbsp;</td></tr>
                <!--<validator target="teamname" required=" - �����������!" maxlength=" - 60 �������� max;60" change="true" place="teamname-messages" />-->
            <tr><td>������� ��������� (*)</td>
                <td><input type="text" maxlen="100" size="20" name="teameducation" value="<?=_data('team')->getEducation()?>" /></td>
                <td id="teameducation-messages">&nbsp;</td></tr>
                <!--<validator target="teameducation" required=" - �����������!" maxlength=" - 100 �������� max;100" change="true" place="teameducation-messages" />-->
            <tr><td>����� (*)</td>
                <td><input type="text" maxlen="50" size="20" name="teamcity" value="<?=_data('team')->getCity()?>" /></td>
                <td id="teamcity-messages">&nbsp;</td></tr>
                <!--<validator target="teamcity" required=" - �����������!" maxlength=" - 50 �������� max;50" change="true" place="teamcity-messages" />-->

            <tr><td colspan="3"><hr /></td></tr>

            <tr><td>�������� 1</td>
                <td>
                    <?php
                        $member = _data('team')->getContestantA();
                    ?>
                    <input type="hidden" name="teamcontestantamemberid" value="<?php echo $member->getId(); ?>" />
                    <input type="hidden" name="teamcontestantamemberrole" value="3" />
                    <input type="hidden" name="teamcontestantamemberteamid" value="<?php echo _data('team')->getId(); ?>" />
                    <?php
                        combobox(
                            'teamcontestantamemberuserid',
                            $member->getUser()->isEmpty() ? '' : $member->getUser()->getId(),
                            $member->getUser()->isEmpty() ? $member->getName() : $member->getUser()->getNickName(),
                            './user/list/json?nickname=%q',
                            ''
                        );
                    ?>
                </td><td>
                    <?php
                        if (!$member->getUser()->isEmpty()):
                            if ($member->getUser()->getId() == $curuserid && (_permission_allow_change_info || $is_admin == 1)):
                                ?>
                                    <a href="./changeinfo.php">�������� ������</a>
                                <?php
                            elseif ($member->getUser()->getId() != $curuserid && (_permission_allow_view_user_info || 1 != $is_admin)):
                                ?>
                                    <a href="./userinfo.php?userid=<?php echo $member->getUser()->getId(); ?>">����������� ������</a>
                                <?php
                            endif;
                        endif;
                    ?></td></tr>
            <tr><td>�������� 2</td>
                <td>
                    <?php
                        $member = _data('team')->getContestantB();
                    ?>
                    <input type="hidden" name="teamcontestantbmemberid" value="<?php echo $member->getId(); ?>" />
                    <input type="hidden" name="teamcontestantbmemberrole" value="4" />
                    <input type="hidden" name="teamcontestantbmemberteamid" value="<?php echo _data('team')->getId(); ?>" />
                    <?php
                        combobox(
                            'teamcontestantbmemberuserid',
                            $member->getUser()->isEmpty() ? '' : $member->getUser()->getId(),
                            $member->getUser()->isEmpty() ? $member->getName() : $member->getUser()->getNickName(),
                            './user/list/json?nickname=%q',
                            ''
                        );
                    ?>
                </td><td>
                    <?php
                        if (!$member->getUser()->isEmpty()):
                            if ($member->getUser()->getId() == $curuserid && (_permission_allow_change_info || $is_admin == 1)):
                                ?>
                                    <a href="./changeinfo.php">�������� ������</a>
                                <?php
                            elseif ($member->getUser()->getId() != $curuserid && (_permission_allow_view_user_info || 1 != $is_admin)):
                                ?>
                                    <a href="./userinfo.php?userid=<?php echo $member->getUser()->getId(); ?>">����������� ������</a>
                                <?php
                            endif;
                        endif;
                    ?></td></tr>
            <tr><td>�������� 3</td>
                <td>
                    <?php
                        $member = _data('team')->getContestantC();
                    ?>
                    <input type="hidden" name="teamcontestantcmemberid" value="<?php echo $member->getId(); ?>" />
                    <input type="hidden" name="teamcontestantcmemberrole" value="5" />
                    <input type="hidden" name="teamcontestantcmemberteamid" value="<?php echo _data('team')->getId(); ?>" />
                    <?php
                        combobox(
                            'teamcontestantcmemberuserid',
                            $member->getUser()->isEmpty() ? '' : $member->getUser()->getId(),
                            $member->getUser()->isEmpty() ? $member->getName() : $member->getUser()->getNickName(),
                            './user/list/json?nickname=%q',
                            ''
                        );
                    ?>
                </td><td>
                    <?php
                        if (!$member->getUser()->isEmpty()):
                            if ($member->getUser()->getId() == $curuserid && (_permission_allow_change_info || $is_admin == 1)):
                                ?>
                                    <a href="./changeinfo.php">�������� ������</a>
                                <?php
                            elseif ($member->getUser()->getId() != $curuserid && (_permission_allow_view_user_info || 1 != $is_admin)):
                                ?>
                                    <a href="./userinfo.php?userid=<?php echo $member->getUser()->getId(); ?>">����������� ������</a>
                                <?php
                            endif;
                        endif;
                    ?></td></tr>

            <tr><td colspan="3"><hr /></td></tr>

            <tr><td>������</td>
                <td>
                    <?php
                        $member = _data('team')->getCoach();
                    ?>
                    <input type="hidden" name="teamcoachmemberid" value="<?php echo $member->getId(); ?>" />
                    <input type="hidden" name="teamcoachmemberrole" value="1" />
                    <input type="hidden" name="teamcoachmemberteamid" value="<?php echo _data('team')->getId(); ?>" />
                    <?php
                        combobox(
                            'teamcoachmemberuserid',
                            $member->getUser()->isEmpty() ? '' : $member->getUser()->getId(),
                            $member->getUser()->isEmpty() ? $member->getName() : $member->getUser()->getNickName(),
                            './user/list/json?nickname=%q',
                            ''
                        );
                    ?>
                </td><td>
                    <?php
                        if (!$member->getUser()->isEmpty()):
                            if ($member->getUser()->getId() == $curuserid && (_permission_allow_change_info || $is_admin == 1)):
                                ?>
                                    <a href="./changeinfo.php">�������� ������</a>
                                <?php
                            elseif ($member->getUser()->getId() != $curuserid && (_permission_allow_view_user_info || 1 != $is_admin)):
                                ?>
                                    <a href="./userinfo.php?userid=<?php echo $member->getUser()->getId(); ?>">����������� ������</a>
                                <?php
                            endif;
                        endif;
                    ?></td></tr>
            <tr><td>������������</td>
                <td>
                    <?php
                        $member = _data('team')->getHead();
                    ?>
                    <input type="hidden" name="teamheadmemberid" value="<?php echo $member->getId(); ?>" />
                    <input type="hidden" name="teamheadmemberrole" value="2" />
                    <input type="hidden" name="teamheadmemberteamid" value="<?php echo _data('team')->getId(); ?>" />
                    <?php
                        combobox(
                            'teamheadmemberuserid',
                            $member->getUser()->isEmpty() ? '' : $member->getUser()->getId(),
                            $member->getUser()->isEmpty() ? $member->getName() : $member->getUser()->getNickName(),
                            './user/list/json?nickname=%q',
                            ''
                        );
                    ?>
                </td><td>
                    <?php
                        if (!$member->getUser()->isEmpty()):
                            if ($member->getUser()->getId() == $curuserid && (_permission_allow_change_info || $is_admin == 1)):
                                ?>
                                    <a href="./changeinfo.php">�������� ������</a>
                                <?php
                            elseif ($member->getUser()->getId() != $curuserid && (_permission_allow_view_user_info || 1 != $is_admin)):
                                ?>
                                    <a href="./userinfo.php?userid=<?php echo $member->getUser()->getId(); ?>">����������� ������</a>
                                <?php
                            endif;
                        endif;
                    ?></td></tr>

            <tr><td>&nbsp;</td>
            <td style="text-align:center"><input type="submit" class="submit" name="btnTeamUpdate" value="��������" onclick="return checkUpdateTeamForm();" /></td><td>&nbsp;</td></tr>
        </table>
        <script>
            (function() {
                var members = ['contestanta', 'contestantb', 'contestantc', 'coach', 'head'];

                window.checkUpdateTeamForm = function() {
                    return checkRequired() && checkCurrentUser() && checkEqualUsers();
                };

                var checkRequired = function() {
                    return KIR.validator('frmTeamUpdate').validate();
                };

                var checkCurrentUser = function() {
                    var result = false;
                    $.each(members, function() {
                        <?php echo $curuserid; ?> == $(['[name=team', this, 'memberuserid]'].join('')).val()
                            ? result = true
                            : void(0);
                    });
                    return result || confirm('�� ����������� ������� ���� �� ������� �������. ����� ����� ��� �������� � �������� ����� ��� ����������. ����������?');
                };

                var checkEqualUsers = function() {
                    return (function(result) {
                        if (!result) { alert('� ����� ������� ���������� ����������� ������������.'); }
                        return result;
                    })(
                        (function(checker) {
                            return (function($ids, $names) {
                                return checker($ids) && checker($names);
                            })(
                                    members.map(function(name) {
                                        return $(['[name=team', name, 'memberuserid]'].join(''));
                                    })
                                ,   members.map(function(name) {
                                        return $(['[name=team', name, 'memberuseridEdit]'].join(''));
                                    })
                            )
                        })(
                            function($fields) {
                                var result = true;
                                $.each($fields, function(i, $fieldA) {
                                    $.each($fields, function(j, $fieldB) {
                                        if (i != j && $fieldA.val() == $fieldB.val() && fieldA.val() !== '') {
                                            result = false;
                                        }
                                    });
                                });
                                return result;
                            }
                        )
                    );
                };

            })();
        </script>
    </form>

<?php } ?>
