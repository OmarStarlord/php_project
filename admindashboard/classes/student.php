<?php

class Student
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db->getDb();
    }

    public function addStudent($email, $password, $groupId, $nom, $prenom, $academicYear, $filiereId)
    {
        
        $id_student = rand(100000, 999999);

        $sql = "INSERT INTO student (id_student, email, password, GroupId, AcademicYear, nom_student, prenom_student, FiliereId) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("issiissi", $id_student, $email, $password, $groupId, $academicYear, $nom, $prenom, $filiereId);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "New student added successfully!";
            } else {
                echo "Error adding new student: " . $stmt->error;
            }

            $stmt->close();
        } else {
            die("Query preparation failed: " . $this->db->error);
        }
    }
}
?>
