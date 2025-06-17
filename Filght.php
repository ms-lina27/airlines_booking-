
<?php
require_once __DIR__ . '/../database.php';

class Flight {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getFlightById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM flights WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateAvailableSeats($flight_id, $count) {
        $stmt = $this->conn->prepare("
            UPDATE flights 
            SET available_seats = available_seats - ? 
            WHERE id = ? AND available_seats >= ?
        ");
        return $stmt->execute([$count, $flight_id, $count]);
    }
}
?>
