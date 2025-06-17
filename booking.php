<?php
require_once __DIR__ . '/../database.php';

class Booking {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function createBooking($flight_id, $passengers, $total_price) {
        try {
            // Start transaction
            $this->conn->beginTransaction();

            // Generate unique booking ID
            $booking_id = uniqid("BK_");

            // Insert into bookings table
            $stmt = $this->conn->prepare("
                INSERT INTO bookings (booking_id, flight_id, total_passengers, total_price)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $booking_id,
                $flight_id,
                count($passengers),
                $total_price
            ]);

            // Insert passengers
            $stmt_passenger = $this->conn->prepare("
                INSERT INTO passengers (booking_id, first_name, last_name, email, phone)
                VALUES (?, ?, ?, ?, ?)
            ");

            foreach ($passengers as $passenger) {
                $stmt_passenger->execute([
                    $booking_id,
                    $passenger['first_name'],
                    $passenger['last_name'],
                    $passenger['email'],
                    $passenger['phone']
                ]);
            }

            // Commit transaction
            $this->conn->commit();

            return $booking_id;

        } catch (Exception $e) {
            // Rollback if error
            $this->conn->rollBack();
            throw new Exception("Booking failed: " . $e->getMessage());
        }
    }
}
?>
