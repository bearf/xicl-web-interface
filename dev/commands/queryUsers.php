<?php
function queryUsers($query) {
    return Butler::getDBFacade()->queryUsersByNickName($query);
}
