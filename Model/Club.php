<?php

class Club {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function registerStudentToClub($userId, $clubId) {
        $checkStmt = $this->conn->prepare("SELECT id FROM registrations WHERE user_id = ? AND club_id = ?");
        $checkStmt->bind_param("ii", $userId, $clubId);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $checkStmt->close();
            return false;
        }
        $checkStmt->close();

        $insertStmt = $this->conn->prepare("INSERT INTO registrations (user_id, club_id, registration_date) VALUES (?, ?, NOW())");
        $insertStmt->bind_param("ii", $userId, $clubId);
        
        if ($insertStmt->execute()) {
            $insertStmt->close();
            return true;
        }
        
        $insertStmt->close();
        return false;
    }

    public function cancelRegistration($registrationId) {
        $stmt = $this->conn->prepare("DELETE FROM registrations WHERE id = ?");
        $stmt->bind_param("i", $registrationId);
        
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        
        $stmt->close();
        return false;
    }
}
?>