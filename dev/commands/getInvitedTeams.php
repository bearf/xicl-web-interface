<?php
// запрос названий команд
function getInvitedTeams() {
    return Butler::getDBFacade()->selectInvitedTeams();
} ?>
