<?php

/**
 * @param  $personalInfo PersonalInfo
 * @return bool
 */
function updateTeam($mapping) {
    $team = Butler::getORMManager()->createTeam()->wrap($mapping)->safe();
    return  Butler::getDBFacade()->updateTeam($team)
            && !Butler::getDBFacade()->selectTeamById($team->getId())->isEmpty();
}
