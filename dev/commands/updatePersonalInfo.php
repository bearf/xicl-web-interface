<?php

/**
 * @param  $personalInfo PersonalInfo
 * @return bool
 */
function updatePersonalInfo($mapping) {
    $personalInfo = Butler::getORMManager()->createPersonalInfo()->wrap($mapping)->safe();
    return  Butler::getDBFacade()->updatePersonalInfo($personalInfo)
            && !Butler::getDBFacade()->selectPersonalInfoByUserId($personalInfo->getUserId())->isEmpty();
}
