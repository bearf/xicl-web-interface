<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Admin
 * Date: 08.07.11
 * Time: 17:13
 * To change this template use File | Settings | File Templates.
 */
class ORMManager {


    // *** METHODS ***

    /**
     * @return PersonalInfo
     */
    public function createPersonalInfo() {
        return new PersonalInfo();
    }

    /**
     * @return Team
     */
    public function createTeam() {
        return new Team();
    }

    /**
     * @return Member
     */
    public function createMember() {
        return new Member();
    }

    /**
     * @return User
     */
    public function createUser() {
        return new User();
    }

    /**
     * @return Status
     */
    public function createStatus() {
        return new Status();
    }

    /**
     * @return Problem
     */
    public function createProblem() {
        return new Problem();
    }


} // end of ORMManager
