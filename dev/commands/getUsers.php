<?php

function getUsers() {
    return Butler::getDBFacade()->selectUsers();
}
