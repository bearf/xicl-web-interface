<?php function content($data) {
    global $curuserid; ?>

    <h3>Данные команды / <?php echo _data('team')->getName(); ?></h3>
    <hr />
    <form action="./team/update/" name="frmTeamUpdate" method="post">
        <input type="hidden" name="teamid" value="<?php echo _data('team')->getId(); ?>" />
        <table class="enter">
            <tr><td>Название</td>
                <td><?=_data('team')->getName()?></td>
                <td id="teamname-messages">&nbsp;</td></tr>
            <tr><td>Учебное заведение</td>
                <td><?=_data('team')->getEducation()?></td>
                <td id="teameducation-messages">&nbsp;</td></tr>
            <tr><td>Город</td>
                <td><?=_data('team')->getCity()?></td>
                <td id="teamcity-messages">&nbsp;</td></tr>

            <tr><td colspan="3"><hr /></td></tr>

            <tr><td>Участник 1</td>
                <td><?php
                        $member = _data('team')->getContestantA();
                        if (!$member->getUser()->isEmpty()):
                            if ($member->getUser()->getId() == $curuserid && (_permission_allow_change_info || $is_admin == 1)): ?>
                                <a href="./changeinfo.php"><?php echo $member->getUser()->getNickName(); ?></a>
                            <?php elseif ($member->getUser()->getId() != $curuserid && (_permission_allow_view_user_info || 1 != $is_admin)): ?>
                                <a href="./userinfo.php?userid=<?php echo $member->getUser()->getId(); ?>"><?php echo $member->getUser()->getNickName(); ?></a>
                            <?php endif;
                        elseif ('' != $member->getName()):
                            echo $member->getName();
                        else: ?>
                            -
                        <?php endif;
                    ?></td></tr>
            <tr><td>Участник 2</td>
                <td>
                    <?php
                        $member = _data('team')->getContestantB();
                        if (!$member->getUser()->isEmpty()):
                            if ($member->getUser()->getId() == $curuserid && (_permission_allow_change_info || $is_admin == 1)): ?>
                                <a href="./changeinfo.php"><?php echo $member->getUser()->getNickName(); ?></a>
                            <?php elseif ($member->getUser()->getId() != $curuserid && (_permission_allow_view_user_info || 1 != $is_admin)): ?>
                                <a href="./userinfo.php?userid=<?php echo $member->getUser()->getId(); ?>"><?php echo $member->getUser()->getNickName(); ?></a>
                            <?php endif;
                        elseif ('' != $member->getName()):
                            echo $member->getName();
                        else: ?>
                            -
                        <?php endif;
                    ?></td></tr>
            <tr><td>Участник 3</td>
                <td>
                    <?php
                        $member = _data('team')->getContestantC();
                        if (!$member->getUser()->isEmpty()):
                            if ($member->getUser()->getId() == $curuserid && (_permission_allow_change_info || $is_admin == 1)): ?>
                                <a href="./changeinfo.php"><?php echo $member->getUser()->getNickName(); ?></a>
                            <?php elseif ($member->getUser()->getId() != $curuserid && (_permission_allow_view_user_info || 1 != $is_admin)): ?>
                                <a href="./userinfo.php?userid=<?php echo $member->getUser()->getId(); ?>"><?php echo $member->getUser()->getNickName(); ?></a>
                            <?php endif;
                        elseif ('' != $member->getName()):
                            echo $member->getName();
                        else: ?>
                            -
                        <?php endif;
                    ?></td></tr>

            <tr><td colspan="3"><hr /></td></tr>

            <tr><td>Тренер1</td>
                <td>
                    <?php
                        $member = _data('team')->getCoach();
                        if (!$member->getUser()->isEmpty()):
                            if ($member->getUser()->getId() == $curuserid && (_permission_allow_change_info || $is_admin == 1)): ?>
                                <a href="./changeinfo.php"><?php echo $member->getUser()->getNickName(); ?></a>
                            <?php elseif ($member->getUser()->getId() != $curuserid && (_permission_allow_view_user_info || 1 != $is_admin)): ?>
                                <a href="./userinfo.php?userid=<?php echo $member->getUser()->getId(); ?>"><?php echo $member->getUser()->getNickName(); ?></a>
                            <?php endif;
                        elseif ('' != $member->getName()):
                            echo $member->getName();
                        else: ?>
                            -
                        <?php endif;
                    ?></td></tr>
            <tr><td>Руководитель</td>
                <td>
                    <?php
                        $member = _data('team')->getHead();
                        if (!$member->getUser()->isEmpty()):
                            if ($member->getUser()->getId() == $curuserid && (_permission_allow_change_info || $is_admin == 1)): ?>
                                <a href="./changeinfo.php"><?php echo $member->getUser()->getNickName(); ?></a>
                            <?php elseif ($member->getUser()->getId() != $curuserid && (_permission_allow_view_user_info || 1 != $is_admin)): ?>
                                <a href="./userinfo.php?userid=<?php echo $member->getUser()->getId(); ?>"><?php echo $member->getUser()->getNickName(); ?></a>
                            <?php endif;
                        elseif ('' != $member->getName()):
                            echo $member->getName();
                        else: ?>
                            -
                        <?php endif;
                    ?></td></tr>
        </table>
    </form>

<?php } ?>
