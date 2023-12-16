<?php

class Database
{
    private $db;

    public function __construct()
    {
        include_once "config.php";
        $this->db = $db;
    }

    public function getDb()
    {
        return $this->db;
    }
}

?>
