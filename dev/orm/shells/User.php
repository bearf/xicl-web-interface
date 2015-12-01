<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Admin
 * Date: 08.07.11
 * Time: 17:15
 * To change this template use File | Settings | File Templates.
 */
 



    require_once dirname(__FILE__) . '/../../system/orm/shells/shell.php';




    class User extends Shell {




        // *** FIELDS ***

        /**
         * @var int
         */
        protected $id           = NULL;

        /**
         * @var string
         */
        protected $nickname     = NULL;

        /**
         * @var PersonalInfo
         */
        protected $personalInfo = NULL;

        protected $info = NULL;

        protected $city = NULL;

        protected $studyplace = NULL;

        protected $division = NULL;

        protected $tatarstan = NULL;




        // *** METHODS ***

        /**
         * @param  $mapping
         * @return User
         */
        public function wrap($mapping, $prefix = '') {
            $result = parent::wrap(
                array('id', 'nickname', 'info', 'city', 'studyplace',
                    'division', 'tatarstan'),
                'user',
                $mapping,
                $prefix
            );
            $this->personalInfo = Butler::getORMManager()->createPersonalInfo()->wrap($mapping, $prefix.'user');
            return $result;
        }

        /**
         * @return User
         */
        public function safe() {
            return parent::safe(
                array('nickname')
            );
        }

        /**
         * @return bool
         */
        public function isEmpty() {
            return parent::isEmpty(
                array('id')
            );
        }




        // *** GETTERS ***

        public function getId() {
            return $this->id;
        }

        public function getNickName() {
            return $this->nickname;
        }

        public function getPersonalInfo() {
            return $this->personalInfo;
        }

        public function getCity() {
            return $this->city;
        }

        public function getStudyplace() {
            return $this->studyplace;
        }

        public function getInfo() {
            return $this->info;
        }

        public function getDivision() {
            return $this->division;
        }

        public function isTatarstan() {
            return 1 === $this->tatarstan;
        }

    } // end of User
