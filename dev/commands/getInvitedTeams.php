<?php
// ������ �������� ������
function getInvitedTeams() {
    return Butler::getDBFacade()->selectInvitedTeams();
} ?>
