<?php

function getProblems($contestId) {
    return Butler::getDBFacade()->selectProblemsByContestId($contestId);
}
