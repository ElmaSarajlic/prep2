<?php
require_once __DIR__."/../dao/MidtermDao.php";

class MidtermService {
    protected $dao;

    public function __construct(){
        $this->dao = new MidtermDao();
    }

    public function cap_table(){
        return $this->dao->cap_table();
    }

    public function summary(){
        return $this->dao->summary();
    }

    public function investors(){
        return $this->dao->investors();
    }
}
