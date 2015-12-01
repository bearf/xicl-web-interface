<?php

function getTeam($teamId) {
    return Butler::getDBFacade()->selectTeamById($teamId);
}
