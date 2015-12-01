<?php




    class Butler {




        // *** METHODS ***

//        public static function getURLManager() {
//            return new URLManager();
//        }
//
        public static function getDBFacade() {
            return new DBFacade();
        }

        public static function getSQLWrapper() {
            return new MySQLWrapper();
        }
//
//        public static function getExceptionManager() {
//            return new ExceptionManager();
//        }
//
//        public static function getTiler() {
//            return new Tiler();
//        }
//
//        public static function getTileManager() {
//            return new TileManager();
//        }
//
//        public static function getRunnerManager() {
//            return new RunnerManager();
//        }

        public static function getQueryManager() {
            return new QueryManager();
        }

        public static function getORMManager() {
            return new ORMManager();
        }
//
//        public static function getSessionManager() {
//            return new SessionManager();
//        }
//
//        public static function getConfig() {
//            return new LocalConfig();
//        }




    } // Butler
