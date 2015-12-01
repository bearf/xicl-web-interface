<?php

// todo: exceptions
// todo: rows wrapper
// todo: Mapping class
// todo: rows wrapper
class DBFacade {




    // *** METHODS

    /**
     * @param  $userId int
     * @return PersonalInfo
     */
    public function selectPersonalInfoByUserId($userId) {
        Butler::getSQLWrapper()->begin();
        $rows = Butler::getSQLWrapper()->query(
            Butler::getQueryManager()->getSelectPersonalInfoByUserIdSQL($userId)
        );
        $mapping = Butler::getSQLWrapper()->fetch($rows);
        Butler::getSQLWrapper()->end();
        return Butler::getORMManager()->createPersonalInfo()->wrap($mapping);
    } // end of selectPostById

    public function selectStatusByContestId($contestId, $since) {
        $status = array();
        Butler::getSQLWrapper()->begin();
        $statusRows = Butler::getSQLWrapper()->query(
            Butler::getQueryManager()->getSelectStatusSQL($contestId, $since)
        );
        while ($statusMapping = Butler::getSQLWrapper()->fetch($statusRows)) {
            $status[] = Butler::getORMManager()->createStatus()->wrap($statusMapping);
        }
        Butler::getSQLWrapper()->end();
        return $status;
    }

    public function selectUsers() {
        $users = array();
        Butler::getSQLWrapper()->begin();
        $userRows = Butler::getSQLWrapper()->query(
            Butler::getQueryManager()->getSelectUserSQL()
        );
        while ($userMapping = Butler::getSQLWrapper()->fetch($userRows)) {
            $users[] = Butler::getORMManager()->createUser()->wrap($userMapping);
        }
        Butler::getSQLWrapper()->end();
        return $users;
    }

    public function selectProblemsByContestId($contestId) {
        $problems = array();
        Butler::getSQLWrapper()->begin();
        $rows = Butler::getSQLWrapper()->query(
            Butler::getQueryManager()->getSelectProblemsSQL($contestId)
        );
        while ($problemMapping = Butler::getSQLWrapper()->fetch($rows)) {
            $problems[] = Butler::getORMManager()->createProblem()->wrap($problemMapping);
        }
        Butler::getSQLWrapper()->end();
        return $problems;
    }

    /**
     * @param  $userId int
     * @return PersonalInfo
     */
    public function createPersonalInfoByUserId($userId) {
        Butler::getSQLWrapper()->begin();
        Butler::getSQLWrapper()->query(
            Butler::getQueryManager()->getCreatePersonalInfoByUserIdSQL($userId)
        );
        Butler::getSQLWrapper()->end();
        return $this->selectPersonalInfoByUserId($userId);
    } // end of createPersonalInfoByUserId

    /**
     * @param  $personalInfo PersonalInfo
     * @return mixed
     */
    public function updatePersonalInfo($personalInfo) {
        Butler::getSQLWrapper()->begin();
        $result = Butler::getSQLWrapper()->query(
            Butler::getQueryManager()->getUpdatePersonalInfoSQL($personalInfo)
        );
        Butler::getSQLWrapper()->end();
        return $result;
    } // end of updatePersonalInfo

    /**
     * @param  $personalInfo PersonalInfo
     * @return mixed
     */
    public function deletePersonalInfo($personalInfo) {
        Butler::getSQLWrapper()->begin();
        $result = Butler::getSQLWrapper()->query(
            Butler::getQueryManager()->getDeletePersonalInfoByIdSQL($personalInfo->getId())
        );
        Butler::getSQLWrapper()->end();
        return $result;
    } // end of deletePersonalInfo

    /**
     * @param  $teamId
     * @return Team
     */
    public function selectTeamById($teamId) {
        Butler::getSQLWrapper()->begin();
        $rows = Butler::getSQLWrapper()->query(
            Butler::getQueryManager()->getSelectTeamByUserIdSQL($teamId)
        );
        $mapping = Butler::getSQLWrapper()->fetch($rows);
        Butler::getSQLWrapper()->end();
        $team = Butler::getORMManager()->createTeam()->wrap($mapping);

        Butler::getSQLWrapper()->begin();
        $rows = Butler::getSQLWrapper()->query(
            Butler::getQueryManager()->getSelectMembersByTeamIdSQL($teamId)
        );
        while ($mapping = Butler::getSQLWrapper()->fetch($rows)) {
            $member = Butler::getORMManager()->createMember()->wrap($mapping);
            switch($member->getRole()) {
                case 1: $team->setCoach($member); break;
                case 2: $team->setHead($member); break;
                case 3: $team->setContestantA($member); break;
                case 4: $team->setContestantB($member); break;
                case 5: $team->setContestantC($member); break;
            }
        }
        Butler::getSQLWrapper()->end();

        return $team;
    } // end of selectTeamByUserId

    public function selectInvitedTeams() {
        $teams = array();
        Butler::getSQLWrapper()->begin();
        $teamRows = Butler::getSQLWrapper()->query(
            Butler::getQueryManager()->getSelectInvitedTeamsSQL()
        );
        while ($teamMapping = Butler::getSQLWrapper()->fetch($teamRows)) {
            $team = Butler::getORMManager()->createTeam()->wrap($teamMapping);
            // todo: another wrapper
            Butler::getSQLWrapper()->begin();
            $memberRows = Butler::getSQLWrapper()->query(
                Butler::getQueryManager()->getSelectMembersByTeamIdSQL($team->getId())
            );
            while ($memberMapping = Butler::getSQLWrapper()->fetch($memberRows)) {
                $member = Butler::getORMManager()->createMember()->wrap($memberMapping);
                switch($member->getRole()) {
                    case 1: $team->setCoach($member); break;
                    case 2: $team->setHead($member); break;
                    case 3: $team->setContestantA($member); break;
                    case 4: $team->setContestantB($member); break;
                    case 5: $team->setContestantC($member); break;
                }
            }
            Butler::getSQLWrapper()->end();
            $teams[] = $team;
        }
        Butler::getSQLWrapper()->end();
        return $teams;
    }

    public function queryUsersByNickName($nickName) {
        $users = array();

        Butler::getSQLWrapper()->begin();
        $rows = Butler::getSQLWrapper()->query(
            Butler::getQueryManager()->getQueryUsersByNickNameSQL($nickName)
        );
        while ($mapping = Butler::getSQLWrapper()->fetch($rows)) {
            $users[] = Butler::getORMManager()->createUser()->wrap($mapping);
        }
        Butler::getSQLWrapper()->end();

        return $users;
    } // end of queryUsersByNickName

    /**
     * @param  $member Member
     * @return mixed
     */
    private function deleteMember($member) {
        Butler::getSQLWrapper()->begin();
        $result = Butler::getSQLWrapper()->query(
            Butler::getQueryManager()->getDeleteMemberSQL($member)
        );
        Butler::getSQLWrapper()->end();
        return $result;
    }

    /**
     * @param  $member Member
     * @return mixed
     */
    private function insertMember($member) {
        Butler::getSQLWrapper()->begin();
        $result = Butler::getSQLWrapper()->query(
            Butler::getQueryManager()->getInsertMemberSQL($member)
        );
        Butler::getSQLWrapper()->end();
        return $result;
    }

    /**
     * @param  $member Member
     * @return mixed
     */
    private function updateMember($member) {
        Butler::getSQLWrapper()->begin();
        $result = Butler::getSQLWrapper()->query(
            Butler::getQueryManager()->getUpdateMemberSQL($member)
        );
        Butler::getSQLWrapper()->end();
        return $result;
    }

    /**
     * @param  $member Member
     * @return mixed
     */
    private function updateTeamMember($member) {
        if (!$member->getId()) {
            return $this->insertMember($member);
        } else {
            return $this->updateMember($member);
        }
    }

    /**
     * @param  $team Team
     * @return mixed
     */
    public function updateTeam($team) {
        Butler::getSQLWrapper()->begin();
        $result = Butler::getSQLWrapper()->query(
            Butler::getQueryManager()->getUpdateTeamSQL($team)
        );
        Butler::getSQLWrapper()->end();

        if ($result) {
            if (!$team->getContestantA()->getName() && $team->getContestantA()->getUser()->isEmpty()) {
                $result = $result && $this->deleteMember($team->getContestantA());
            } else {
                $result = $result && $this->updateTeamMember($team->getContestantA());
            }
            if (!$team->getContestantB()->getName() && $team->getContestantB()->getUser()->isEmpty()) {
                $result = $result && $this->deleteMember($team->getContestantB());
            } else {
                $result = $result && $this->updateTeamMember($team->getContestantB());
            }
            if (!$team->getContestantC()->getName() && $team->getContestantC()->getUser()->isEmpty()) {
                $result = $result && $this->deleteMember($team->getContestantC());
            } else {
                $result = $result && $this->updateTeamMember($team->getContestantC());
            }
            if (!$team->getCoach()->getName() && $team->getCoach()->getUser()->isEmpty()) {
                $result = $result && $this->deleteMember($team->getCoach());
            } else {
                $result = $result && $this->updateTeamMember($team->getCoach());
            }
            if (!$team->getHead()->getName() && $team->getHead()->getUser()->isEmpty()) {
                $result = $result && $this->deleteMember($team->getHead());
            } else {
                $result = $result && $this->updateTeamMember($team->getHead());
            }
        }

        return $result;
    } // end of updateTeam

}
