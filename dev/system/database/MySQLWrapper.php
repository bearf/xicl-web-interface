<?php




    class MySQLWrapper {




        // *** METHODS ***

        /**
         * @return mysqli
         */
        private function getMysqli() {
            global $mysqli_;
            return $mysqli_;
            //return Registry::get(Defines::$LINK);
        }

        public function query($sql) {
            return $this->getMysqli()->query($sql);
        }

        public function begin() {

        }

        public function end() {

        }

        public function fetch($rows) {
            return $rows->fetch_array();
        }

        /**
         * @param  $value string
         * @return string
         */
        public function safe($value) {
            return $this->getMysqli()->real_escape_string($value);
        }




    } // MySQLWrapper
