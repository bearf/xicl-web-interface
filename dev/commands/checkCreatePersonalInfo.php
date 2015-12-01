<?php

/**
 * @param  $userId int
 * @return bool
 */
function checkCreatePersonalInfo($userId) {
    return Butler::getDBFacade()->selectPersonalInfoByUserId($userId)->isEmpty();
}
