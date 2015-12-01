<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Admin
 * Date: 08.07.11
 * Time: 17:15
 * To change this template use File | Settings | File Templates.
 */
 



    require_once dirname(__FILE__) . '/../../system/orm/shells/shell.php';




    class Member extends Shell {




        // *** FIELDS ***

        /**
         * @var int
         */
        protected $id           = NULL;

        /**
         * @var int
         */
        protected $teamid       = NULL;

        /**
         * @var User
         */
        protected $user         = NULL;

        /**
         * @var string
         */
        protected $name         = NULL;

        /**
         * @var int
         */
        protected $role         = NULL;




        // *** CONSTRUCTORS ***

        public function __construct() {
            $this->user = Butler::getORMManager()->createUser();
        }




        // *** METHODS ***

        /**
         * @param  $mapping
         * @return Member
         */
        public function wrap($mapping, $prefix = '') {
            $result = parent::wrap(
                array('id', 'name', 'role', 'teamid'),
                'member',
                $mapping,
                $prefix
            );
            $this->user = Butler::getORMManager()->createUser()->wrap($mapping, $prefix.'member');
            return $result;
        }

        /**
         * @return Member
         */
        public function safe() {
            return parent::safe(
                array('name')
            );
        }

        /**
         * @return Team
         */
        public function unsafe() {
            return parent::unsafe(
                array('name')
            );
        }

        /**
         * @return bool
         */
        public function isEmpty() {
            return parent::isEmpty(
                array('id', 'name', 'role')
            );
        }




        // *** GETTERS ***

        public function getId() {
            return $this->id;
        }

        public function getUser() {
            return $this->user;
        }

        public function getName() {
            return $this->name;
        }

        public function getRole() {
            return $this->role;
        }

        public function getTeamId() {
            return $this->teamid;
        }




    } // end of Post
