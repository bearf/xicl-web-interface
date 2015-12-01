<?php

function selectPersonalInfo($userId) {
    return Butler::getDBFacade()->selectPersonalInfoByUserId($userId);
}

