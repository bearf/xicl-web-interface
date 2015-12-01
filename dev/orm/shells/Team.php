<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Admin
 * Date: 08.07.11
 * Time: 17:15
 * To change this template use File | Settings | File Templates.
 */
 



    require_once dirname(__FILE__) . '/../../system/orm/shells/shell.php';




    class Team extends Shell {




        // *** FIELDS ***

        /**
         * @var int
         */
        protected $id           = NULL;

        /**
         * @var string
         */
        protected $name         = NULL;

        /**
         * @var string
         */
        protected $education    = NULL;

        /**
         * @var string
         */
        protected $city         = NULL;

        /**
         * @var Member
         */
        protected $coach        = NULL;

        /**
         * @var Member
         */
        protected $head         = NULL;

        /**
         * @var Member
         */
        protected $contestantA  = NULL;

        /**
         * @var Member
         */
        protected $contestantB  = NULL;

        /**
         * @var Member
         */
        protected $contestantC  = NULL;




        // *** CONSTRUCTORS ***

        public function __construct() {
            $this->head = Butler::getORMManager()->createMember();
            $this->coach = Butler::getORMManager()->createMember();
            $this->contestantA = Butler::getORMManager()->createMember();
            $this->contestantB = Butler::getORMManager()->createMember();
            $this->contestantC = Butler::getORMManager()->createMember();
        }




        // *** METHODS ***

        /**
         * @param  $mapping
         * @return Team
         */
        public function wrap($mapping) {
            $result = parent::wrap(
                array('id', 'name', 'education', 'city'),
                'team',
                $mapping
            );
            $this->setContestantA(Butler::getORMManager()->createMember()->wrap($mapping, 'teamcontestanta'));
            $this->setContestantB(Butler::getORMManager()->createMember()->wrap($mapping, 'teamcontestantb'));
            $this->setContestantC(Butler::getORMManager()->createMember()->wrap($mapping, 'teamcontestantc'));
            $this->setHead(Butler::getORMManager()->createMember()->wrap($mapping, 'teamhead'));
            $this->setCoach(Butler::getORMManager()->createMember()->wrap($mapping, 'teamcoach'));
            return $result;
        }

        /**
         * @return Team
         */
        public function safe() {
            return parent::safe(
                array('name','education', 'city',
                     'contestantA', 'contestantB', 'contestantC', 'coach', 'head')
            );
        }

        /**
         * @return Team
         */
        public function unsafe() {
            return parent::unsafe(
                array('name', 'education', 'city',
                     'contestantA', 'contestantB', 'contestantC', 'coach', 'head')
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

        public function getName() {
            return $this->name;
        }

        public function getEducation() {
            return $this->education;
        }

        public function getCity() {
            return $this->city;
        }

        public function getCoach() {
            return $this->coach;
        }

        public function getHead() {
            return $this->head;
        }

        public function getContestantA() {
            return $this->contestantA;
        }

        public function getContestantB() {
            return $this->contestantB;
        }

        public function getContestantC() {
            return $this->contestantC;
        }




        // *** SETTERS ***

        public function setCoach($coach) {
            $this->coach = $coach;
        }

        public function setHead($head) {
            $this->head = $head;
        }

        public function setContestantA($contestantA) {
            $this->contestantA = $contestantA;
        }

        public function setContestantB($contestantB) {
            $this->contestantB = $contestantB;
        }

        public function setContestantC($contestantC) {
            $this->contestantC = $contestantC;
        }




    } // end of Post
