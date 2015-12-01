<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Admin
 * Date: 08.07.11
 * Time: 17:15
 * To change this template use File | Settings | File Templates.
 */
 



    require_once dirname(__FILE__) . '/../../system/orm/shells/shell.php';




    class PersonalInfo extends Shell {




        // *** FIELDS ***

        protected $id           = NULL;

        /**
         * @var int
         */
        protected $userId       = NULL;

        // --- info ---

        /**
         * @var string
         */
        protected $surname      = NULL;

        /**
         * @var string
         */
        protected $name         = NULL;

        /**
         * @var string
         */
        protected $patrName     = NULL;

        /**
         * @var string
         */
        protected $birthDate    = NULL;

        // --- passport & documents ---

        /**
         * @var string
         */
        protected $passportNo   = NULL;

        /**
         * @var string
         */
        protected $passportDate = NULL;

        /**
         * @var string
         */
        protected $passportIssue= NULL;

        /**
         * @var string
         */
        protected $PTPN         = NULL;

        // --- contact ---

        /**
         * @var string
         */
        protected $phone        = NULL;

        // --- address ---

        /**
         * @var string
         */
        protected $region       = NULL;

        /**
         * @var string
         */
        protected $city         = NULL;

        /**
         * @var string
         */
        protected $postIndex    = NULL;

        /**
         * @var string
         */
        protected $address      = NULL;



        // *** METHODS ***

        /**
         * @param  $mapping
         * @return PersonalInfo
         */
        public function wrap($mapping, $prefix = '') {
            $result = parent::wrap(
                array(
                    'id', 'userId',
                    'surname', 'name', 'patrName', 'birthDate',
                    'passportNo', 'passportDate', 'passportIssue', 'PTPN',
                    'phone',
                    'region', 'city', 'postIndex', 'address'
                ),
                'persinfo',
                $mapping,
                $prefix
            );
            return $result;
        }

        /**
         * @return PersonalInfo
         */
        public function safe() {
            return parent::safe(
                array(
                    'surname', 'name', 'patrName', 'birthDate',
                    'passportNo', 'passportDate', 'passportIssue', 'PTPN',
                    'phone',
                    'region', 'city', 'postIndex', 'address'
                )
            );
        }

        /**
         * @return bool
         */
        public function isEmpty() {
            return parent::isEmpty(
                array('id', 'userId')
            );
        }




        // *** GETTERS ***

        public function getId() {
            return $this->id;
        }

        public function getUserId() {
            return $this->userId;
        }

        public function getSurname() {
            return $this->surname;
        }

        public function getName() {
            return $this->name;
        }

        public function getPatrName() {
            return $this->patrName;
        }

        public function getBirthDate() {
            return $this->birthDate;
        }

        public function getPassportNo() {
            return $this->passportNo;
        }

        public function getPassportDate() {
            return $this->passportDate;
        }

        public function getPassportIssue() {
            return $this->passportIssue;
        }

        public function getPTPN() {
            return $this->PTPN;
        }

        public function getPhone() {
            return $this->phone;
        }

        public function getRegion() {
            return $this->region;
        }

        public function getCity() {
            return $this->city;
        }

        public function getPostIndex() {
            return $this->postIndex;
        }

        public function getAddress() {
            return $this->address;
        }




    } // end of Post
