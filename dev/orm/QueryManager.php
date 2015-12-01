<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Admin
 * Date: 08.07.11
 * Time: 17:12
 * To change this template use File | Settings | File Templates.
 */


class QueryManager {




    // *** CONSTS ***

    // *** METHODS ***

    private function getSelectPersonalInfoTemplateSQL() {
        return implode(' ', array(
            'SELECT', implode(',', array(
               'persinfo.persInfoId as persinfoid',
               'persinfo.userId as persinfouserId',

               'persinfo.surname as persinfosurname',
               'persinfo.name as persinfoname',
               'persinfo.patrName as persinfopatrName',
               'persinfo.birthDate as persinfobirthDate',

               'persinfo.passportNo as persinfopassportNo',
               'persinfo.passportDate as persinfopassportDate',
               'persinfo.passportIssue as persinfopassportIssue',
               'persinfo.PTPN as persinfoPTPN',

               'persinfo.phone as persinfophone',

               'persinfo.region as persinforegion',
               'persinfo.city as persinfocity',
               'persinfo.postIndex as persinfopostIndex',
               'persinfo.address as persinfoaddress'
             )),
            'FROM', 'persInfo'
        ));
    }

    public function getSelectStatusSQL($contestId, $since) {
        return sprintf(implode(' ', array(
                'SELECT', implode(',', array(
                    'submit.submitId as statussubmitId',
                    'submit.userId as statususerId',
                    'volume.taskId as statustaskId',
                    'UNIX_TIMESTAMP(submit.submitTime) as statustimestamp',
                    'TIME_TO_SEC(TIMEDIFF(submit.submitTime, cntest.start)) as statustime',
                    'lang.ext as statuslangId',
                    'submit.resultId as statusresult,
                    U.nickname as statusnickname,
                    U.info as statusinfo,
                    U.studyplace as statusstudyplace,
                    U.city as statuscity,
                    U.division as statusdivision,
                    U.tatarstan as statustatarstan,
                    volume.problemId as statustask'
                )),
                'FROM',
                    '((((submit INNER JOIN volume on submit.problemId = volume.problemId AND submit.contestid = volume.contestId) INNER JOIN cntest on volume.contestId = cntest.contestId)',
                    'INNER JOIN lang ON submit.langId = lang.langId)',
                    'INNER JOIN testing T on submit.submitId = T.submitId)
                    INNER JOIN user U on submit.userId = U.id
                WHERE',
                    'submit.contestId = "%d" AND T.result = 0 AND (submit.resultId = 21 OR submit.resultId = 255 OR submit.totalTime > 0 OR submit.totalMemory > 0) /* tested solutions */
                    AND submit.submitId > ' . (NULL !== $since ? $since : 0) . '
                ORDER BY',
                    'submit.submitId ASC'

            )),
            $contestId
        );
    }

    public function getSelectUserSQL() {
        return implode(' ', array(
                'SELECT', implode(',', array( '
                    U.id as userid,
                    U.nickname as usernickname,
                    U.info as userinfo,
                    U.studyplace as userstudyplace,
                    U.city as usercity,
                    U.division as userdivision,
                    U.tatarstan as usertatarstan'
                )),
                'FROM',
                    'user U'
            )
        );
    }

    public function getSelectProblemsSQL($contestId) {
        return sprintf('SELECT 
                volume.id as problemid, problemId as problemletter, time_to_sec(timediff(added, start)) as problempublishedAfter
            FROM
                volume inner join cntest on volume.contestId = cntest.contestId
            WHERE
                volume.contestId = "%d"
            ORDER BY problemletter ASC', $contestId);
    }

    public function getSelectInvitedTeamsSQL() {
        return implode(' ', array(
            'SELECT', implode(',', array(
                'teamid as teamid',
                'teamname as teamname',
                'education as teameducation',
                'city as teamcity'
            )),
            'FROM',
                'teams T',
            'WHERE',
                'T.invited = "1"',
            'ORDER BY',
                'T.teamid ASC'
        ));
    }

    /**
     * @param  $userId
     * @return string
     */
    public function getSelectPersonalInfoByUserIdSQL($userId) {
        return sprintf(
            implode(' ', array(
                $this->getSelectPersonalInfoTemplateSQL(),
                'WHERE persinfo.userId = %d',
                'LIMIT 1'
            )), $userId
        );
    }

    /**
     * @param  $userId int
     * @return string
     */
    public function getCreatePersonalInfoByUserIdSQL($userId) {
        $fields = array('userId',   'surname',  'name', 'patrName', 'birthDate',    'passportNo',   'passportDate', 'passportIssue',    'PTPN', 'phone',    'region',   'city', 'postIndex',    'address');
        $values = array($userId,    '""',       '""',   '""',       '""',           '""',           '""',           '""',               '""',   '""',       '""',       '""',   '""',           '""');
        return sprintf(
            'INSERT INTO persInfo(%s) VALUES(%s)' ,
            implode(',', $fields),
            implode(',', $values)
        );
    }

    /**
     * @param  $personalInfo PersonalInfo
     * @return string
     */
    public function getUpdatePersonalInfoSQL($personalInfo) {
        $pairs = array(
            implode('=', array('surname',       sprintf('\'%s\'', $personalInfo->getSurname()))),
            implode('=', array('name',          sprintf('\'%s\'', $personalInfo->getName()))),
            implode('=', array('patrName',      sprintf('\'%s\'', $personalInfo->getPatrName()))),
            implode('=', array('birthDate',     sprintf('\'%s\'', $personalInfo->getBirthDate()))),

            implode('=', array('passportNo',    sprintf('\'%s\'', $personalInfo->getPassportNo()))),
            implode('=', array('passportDate',  sprintf('\'%s\'', $personalInfo->getPassportDate()))),
            implode('=', array('passportIssue', sprintf('\'%s\'', $personalInfo->getPassportIssue()))),
            implode('=', array('PTPN',          sprintf('\'%s\'', $personalInfo->getPTPN()))),

            implode('=', array('phone',         sprintf('\'%s\'', $personalInfo->getPhone()))),

            implode('=', array('region',        sprintf('\'%s\'', $personalInfo->getRegion()))),
            implode('=', array('city',          sprintf('\'%s\'', $personalInfo->getCity()))),
            implode('=', array('postIndex',     sprintf('\'%s\'', $personalInfo->getPostIndex()))),
            implode('=', array('address',       sprintf('\'%s\'', $personalInfo->getAddress())))
        );
        return sprintf(
            'UPDATE persInfo SET %s WHERE persInfoId = "%d"',
            implode(',', $pairs),
            $personalInfo->getId()
        );
    }

    /**
     * @param  $personalInfoId int
     * @return string
     */
    public function getDeletePersonalInfoByIdSQL($personalInfoId) {
        return sprintf(
            'DELETE FROM persInfo WHERE persInfoId = "%d"',
            $personalInfoId
        );
    }

    /**
     * @param  $teamId int
     * @return string
     */
    public function getSelectTeamByUserIdSQL($teamId) {
        return sprintf(implode(' ', array(
                'SELECT', implode(',', array(
                    'teamid as teamid',
                    'teamname as teamname',
                    'education as teameducation',
                    'city as teamcity'
                )),
                'FROM',
                    'teams T',
                'WHERE',
                    'T.teamId = "%d"'
            )),
            $teamId
        );
    }

    public function getSelectMembersByTeamIdSQL($teamId) {
        return sprintf(implode(' ', array(
                'SELECT', implode(',', array(
                    'M.memberId as memberid',
                    'M.name as membername',
                    'M.role as memberrole',
                    'U.id as memberuserid',
                    'U.nickname as memberusernickname',

                    'P.persInfoId as memberuserpersinfoid',
                    'P.userId as memberuserpersinfouserId',

                    'P.surname as memberuserpersinfosurname',
                    'P.name as memberuserpersinfoname',
                    'P.patrName as memberuserpersinfopatrName',
                    'P.birthDate as memberuserpersinfobirthDate',

                    'P.passportNo as memberuserpersinfopassportNo',
                    'P.passportDate as memberuserpersinfopassportDate',
                    'P.passportIssue as memberuserpersinfopassportIssue',
                    'P.PTPN as memberuserpersinfoPTPN',

                    'P.phone as memberuserpersinfophone',

                    'P.region as memberuserpersinforegion',
                    'P.city as memberuserpersinfocity',
                    'P.postIndex as memberuserpersinfopostIndex',
                    'P.address as memberuserpersinfoaddress'
                )),
                'FROM',
                    '(members M LEFT JOIN `user` U on M.userId = U.id) LEFT JOIN persInfo P on U.id = P.userId',
                'WHERE',
                    'M.teamId = "%d"',
                'ORDER BY',
                    'M.memberId ASC'
            )),
            $teamId
        );
    }

    public function getQueryUsersByNickNameSQL($nickName) {
        return sprintf(implode(' ', array(
                'SELECT', implode(',', array(
                    'U.id as userid',
                    'U.nickname as usernickname'
                )),
                'FROM',
                    '`user` U',
                'WHERE',
                    'U.nickname LIKE "%s%%"',
                'LIMIT 10'
            )),
            $nickName
        );
    }

    /**
     * @param  $team Team
     * @return string
     */
    public function getUpdateTeamSQL($team) {
        return sprintf(implode(' ', array(
                'UPDATE teams SET teamname = "%s", education = "%s", city = "%s" WHERE teamId = "%d"'
            )),
            $team->getName(),
            $team->getEducation(),
            $team->getCity(),
            $team->getId()
        );
    }

    /**
     * @param  $member Member
     * @return string
     */
    public function getDeleteMemberSQL($member) {
        return sprintf(
            'DELETE FROM members WHERE teamId = "%d" AND role = "%d"',
            $member->getTeamId(),
            $member->getRole()
        );
    }

    /**
     * @param  $member Member
     * @return string
     */
    public function getInsertMemberSQL($member) {
        $fields = array('teamId',               'userId',                                   'name',                                 'role');
        $values = array($member->getTeamId(),   sprintf("%d", $member->getUser()->getId()), sprintf('"%s"', $member->getName()),    $member->getRole());
        return sprintf(
            'INSERT INTO members(%s) VALUES(%s)' ,
            implode(',', $fields),
            implode(',', $values)
        );
    }

    /**
     * @param  $member Member
     * @return string
     */
    public function getUpdateMemberSQL($member) {
        $pairs = array(
            implode('=', array('name',      sprintf('"%s"', $member->getName()))),
            implode('=', array('userId',    sprintf('"%d"', $member->getUser()->getId()))),
        );
        return sprintf(
            'UPDATE members SET %s WHERE memberId = "%d"',
            implode(',', $pairs),
            $member->getId()
        );
    }




} // end of QueryManager
