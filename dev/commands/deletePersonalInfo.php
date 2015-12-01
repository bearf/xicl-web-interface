<?php

/**
 * @param  $mapping array
 * @return bool
 */
function deletePersonalInfo($mapping) {
    $personalInfo = Butler::getORMManager()->createPersonalInfo()->wrap($mapping);
    return  Butler::getDBFacade()->deletePersonalInfo($personalInfo)
            && Butler::getDBFacade()->selectPersonalInfoByUserId($personalInfo->getUserId())->isEmpty();
}
