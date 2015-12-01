<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Admin
 * Date: 08.07.11
 * Time: 17:15
 * To change this template use File | Settings | File Templates.
 */

require_once dirname(__FILE__) . '/../../system/orm/shells/shell.php';

class Status extends Shell {

    // *** FIELDS ***

    /**
     * @var int
     */
    protected $submitId     = NULL;

    /**
     * @var int
     */
    protected $taskId       = NULL;

    /**
     * @var int
     */
    protected $userId       = NULL;

    /**
     * @var string
     */
    protected $langId       = NULL;

    /**
     * @var string
     */
    protected $time         = NULL;

    /**
     * @var string
     */
    protected $result       = NULL;

    /**
     * @var string
     */
    protected $task = NULL;

    /**
     * @var string
     */
    protected $nickname = NULL;

    protected $info = NULL;

    protected $studyplace = NULL;

    protected $city = NULL;

    protected $division = NULL;

    protected $tatarstan = NULL;


    // *** METHODS ***

    /**
     * @param  $mapping
     * @return Status
     */
    public function wrap($mapping, $prefix = '') {
        $result = parent::wrap(
            array('submitId', 'taskId', 'userId', 'langId', 
                'time', 'result', 'task', 'nickname', 'info',
                'studyplace', 'city', 'division', 'tatarstan'),
            'status',
            $mapping,
            $prefix
        );
        switch($this->result) {
            case '0': $this->result = 'OK'; break;
            case '255': $this->result = 'FL'; break;
            case '21': $this->result = 'CE'; break;
        }
        if ($this->result%100 == 1) {
            $this->result = 'WA' . (int)($this->result / 100);
        } elseif ($this->result%100 == 2) {
            $this->result = 'PE' . (int)($this->result / 100);
        } elseif ($this->result%100 == 3) {
            $this->result = 'TL' . (int)($this->result / 100);
        } elseif ($this->result%100 == 4) {
            $this->result = 'RE' . (int)($this->result / 100);
        } elseif ($this->result%100 == 20) {
            $this->result = 'ML' . (int)($this->result / 100);
        } elseif ($this->result%100 == 23) {
            $this->result = 'DL' . (int)($this->result / 100);
        }
        return $result;
    }

    /**
     * @return Status
     */
    public function safe() {
        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty() {
        return parent::isEmpty(
            array('submitId')
        );
    }


    // *** GETTERS ***

    public function getSubmitId() {
        return $this->submitId;
    }

    public function getTaskId() {
        return $this->taskId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getLangId() {
        return $this->langId;
    }

    public function getTime() {
        return $this->time;
    }

    public function getResult() {
        return $this->result;
    }

    public function getTask() {
        return $this->task;
    }

    public function getNickname() {
        return $this->nickname;
    }

    public function getInfo() {
        return $this->info;
    }

    public function getStudyplace() {
        return $this->studyplace;
    }

    public function getCity() {
        return $this->city;
    }

    public function getDivision() {
        return $this->division;
    }

    public function isTatarstan() {
        return 1 === $this->tatarstan;
    }

} // end of Status
