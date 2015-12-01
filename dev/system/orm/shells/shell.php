<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Admin
 * Date: 08.07.11
 * Time: 17:14
 * To change this template use File | Settings | File Templates.
 */
 



    abstract class Shell {




        // *** METHODS ***

        // todo: Mapping class
        /**
         * @param  $mapping
         * @return Shell
         */
        protected function wrap($fieldNames, $fieldPrefix, $mapping, $topPrefix = '') {

            foreach($fieldNames as $_ => $fieldName) {
                if (isset($mapping[$topPrefix . $fieldPrefix . $fieldName])) {
                    $this->$fieldName = $mapping[$topPrefix . $fieldPrefix . $fieldName];
                    $set = true;
                }
            }

            return $this;

        }

        protected function isEmpty($fieldNames) {

            $empty = true;

            foreach($fieldNames as $_ => $fieldName) {
                $empty = $empty && (!isset($this->$fieldName) || isset($this->$fieldName) && '' === $this->$fieldName);
            }

            return $empty;

        }

        protected function safe($fieldNames) {

            foreach($fieldNames as $_ => $fieldName) {
                if ('string' == gettype($this->$fieldName)) {
                    $value = preg_replace(
                        array(/*'/\</', '/\>/', */'/\"/'),
                        array(/*'&lt;', '&gt;', */'&quot;'),
                        $this->$fieldName
                    );
                    if (get_magic_quotes_gpc()) {
                        $value = stripslashes($value);
                    }
                    $this->$fieldName = Butler::getSQLWrapper()->safe($value);
                } else if ('object' == gettype($this->$fieldName)) {
                    $class = new ReflectionClass($this->$fieldName);
                    if ($class->hasMethod('safe')) {
                        $this->$fieldName->safe();
                    }
                }
            }

            return $this;

        }

        protected function unsafe($fieldNames) {

            foreach($fieldNames as $_ => $fieldName) {
                if ('string' == gettype($this->$fieldName)) {
                    $value = $this->$fieldName;
                    if (get_magic_quotes_gpc()) {
                        $value = stripslashes($value);
                    }
                    $this->$fieldName = $value;
                } else if ('object' == gettype($this->$fieldName)) {
                    $class = new ReflectionClass($this->$fieldName);
                    if ($class->hasMethod('unsafe')) {
                        $this->$fieldName->unsafe();
                    }
                }
            }

            return $this;

        }




    } // end of Shell
