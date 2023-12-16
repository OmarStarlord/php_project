<?php

class Admin
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db->getDb();
    }

    public function fetchAdmin($username, $password)
    {
        $sql = "SELECT username, password FROM admin WHERE username = ? AND password = ?";
        $stmt = $this->db->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $adminId = $row['username'];
                    // Retrieve other columns as needed
                }
            } else {
                die("No records found for the provided username and password");
            }

            $stmt->close();
        } else {
            die("Query preparation failed: " . $this->db->error);
        }

        return $adminId;
    }
}

?>
