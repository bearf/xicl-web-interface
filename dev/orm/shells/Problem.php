<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Admin
 * Date: 08.07.11
 * Time: 17:15
 * To change this template use File | Settings | File Templates.
 */
 
require_once dirname(__FILE__) . '/../../system/orm/shells/shell.php';


class Problem extends Shell {


    // *** FIELDS ***

    /**
     * @var int
     */
    protected $id = NULL;

    /**
     * @var string
     */
    protected $letter = NULL;

    /**
     * @var string
     */
    protected $publishedAfter = NULL;


    // *** METHODS ***

    /**
     * @param  $mapping
     * @return User
     */
    public function wrap($mapping, $prefix = '') {
        $result = parent::wrap(
            array('id', 'letter', 'publishedAfter'),
            'problem',
            $mapping,
            $prefix
        );
        return $result;
    }

    /**
     * @return Problem
     */
    public function safe() {
        return parent::safe(
            array('letter')
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

    public function getLetter() {
        return $this->letter;
    }

    public function getPublishedAfter() {
        return $this->publishedAfter;
    }


} // end of Problem
