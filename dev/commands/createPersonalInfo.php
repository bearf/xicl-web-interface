<?php

/**
 * @param  $userId int
 * @return bool
 */
function createPersonalInfo($userId) {
    return !Butler::getDBFacade()->createPersonalInfoByUserId($userId)->isEmpty();
}
