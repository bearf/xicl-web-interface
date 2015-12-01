<?php

function getStatus($contestId, $since = 0) {
    return Butler::getDBFacade()->selectStatusByContestId($contestId, $since);
}
