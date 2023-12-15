<?php 

class Student {
    public $firstname;
    public $lastname;
    public $filiereId;
    public $email;
    public $password;
    public $academicYear;


    public function __construct($firstname, $lastname, $filiereId, $email, $password, $academicYear){
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->filiereId = $filiereId;
        $this->email = $email;
        $this->password = $password;
        $this->academicYear = $academicYear;
    }



}