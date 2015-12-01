<?php function content($data) { ?>
    <?php global $is_admin; global $curteamid; ?>
    <?php $instance = _data('instance'); ?>

    <h3>������ ������������</h3>
    <table class="enter">
        <tr><td>Nickname</td><td><?=$instance->Nickname?></td></tr>
        <?php if (isset($instance->teamId)): ?>
            <tr><td>�������</td><td>
                <?php if ($curteamid == $instance->teamId && (_permission_allow_update_team_info || 1 == $is_admin)): ?>
                    <a href="./team/update/"><?php echo $instance->TeamName;?></a>
                <?php elseif (_permission_allow_view_team_info || 1 == $is_admin): ?>
                    <a href="./team/view/?teamid=<?php echo $instance->teamId; ?>"><?php echo $instance->TeamName;?></a>
                <?php else: ?>
                    <?php echo $instance->TeamName;?>
                <?php endif; ?>
            </td></tr>
        <?php endif; ?>
        <?php if ('' !== $instance->Studyplace): ?>
            <tr><td>����� �����</td><td><?=$instance->Studyplace?></td></tr>
        <?php endif; ?>
        <?php if ('' !== $instance->Class): ?>
            <tr><td>�����/����</td><td><?=$instance->Class?></td></tr>
        <?php endif; ?>
        <?php if (($instance->Allowpublish || 1 == $is_admin) && '' !==  $instance->Email): ?>
            <tr><td>E-Mail</td><td><?=$instance->Email?></td></tr>
        <? endif; ?>
        <?php if ('' !== $instance->Info): ?>
            <tr><td>����</td><td><pre style="font-family:verdana,tahoma,sans-serif;font-size:11px;margin:0;"><?=$instance->Info?></pre></td></tr>
        <?php endif; ?>
        <tr><td colspan="2"><hr /></td><td></tr>
        <tr>
            <td colspan="2" class="c">
                ::<a href="status.php?userid=<?=$instance->ID?>">������� ������� ����� � ������� �������</a>
            </td>
        </tr>
    </table>

    <?php if (_has('persInfo')): ?>
        <h3>������������ ������</h3>
        <?php $personalInfo = _data('persInfo'); ?>
        <table class="enter">

            <tr><td>�������</td>
                <td><?=$personalInfo->getSurname()?></td>
                <td id="persinfosurname-messages">&nbsp;</td></tr>
            <tr><td>���</td>
                <td><?=$personalInfo->getName()?></td>
                <td id="persinfoname-messages">&nbsp;</td></tr>
            <tr><td>��������</td>
                <td><?=$personalInfo->getPatrName()?></td>
                <td id="persinfopatrName-messages">&nbsp;</td></tr>
            <tr><td>���� ��������</td>
                <td><?=$personalInfo->getBirthDate()?></td>
                <td id="persinfobirthDate-messages">&nbsp;</td></tr>

            <tr><td colspan="3"><hr /></td></tr>

            <tr><td>����� � ����� ��������</td>
                <td><?=$personalInfo->getPassportNo()?></td>
                <td id="persinfopassportNo-messages">&nbsp;</td></tr>
            <tr><td>����� ����� �������</td>
                <td><?=$personalInfo->getPassportDate()?></td>
                <td id="persinfopassportDate-messages">&nbsp;</td></tr>
            <tr><td>��� ����� �������</td>
                <td><?=$personalInfo->getPassportIssue()?></td>
                <td id="persinfopassportIssue-messages">&nbsp;</td></tr>
            <tr><td>���</td>
                <td><?=$personalInfo->getPTPN()?></td>
                <td id="persinfoPTPN-messages">&nbsp;</td></tr>

            <tr><td colspan="3"><hr /></td></tr>

            <tr><td>���������� �������</td>
                <td><?=$personalInfo->getPhone()?></td>
                <td id="persinfophone-messages">&nbsp;</td></tr>

            <tr><td colspan="3"><hr /></td></tr>

            <tr><td>������</td>
                <td><?=$personalInfo->getRegion()?></td>
                <td id="persinforegion-messages">&nbsp;</td></tr>
            <tr><td>�����</td>
                <td><?=$personalInfo->getCity()?></td>
                <td id="persinfocity-messages">&nbsp;</td></tr>
            <tr><td>�������� ������</td>
                <td><?=$personalInfo->getPostIndex()?></td>
                <td id="persinfopostIndex-messages">&nbsp;</td></tr>
            <tr><td>�����</td>
                <td><?=$personalInfo->getAddress()?></td>
                <td id="persinfoaddress-messages">&nbsp;</td></tr>

        </table>
    <?php endif; ?>
<?php } ?>
