<?php
require_once '../config/database.php';

class Booking {
    private $conn;
    private $bookings_table = "bookings";
    private $passengers_table = "passengers";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function createBooking($flight_id, $passengers, $total_price) {
        try {
            $this->conn->beginTransaction();
            
            // Generate unique booking ID
            $booking_id = 'BK' . date('Ymd') . rand(1000, 9999);
            
            // Insert booking
            $query = "INSERT INTO " . $this->bookings_table . " 
                      (booking_id, flight_id, total_passengers, total_price) 
                      VALUES (:booking_id, :flight_id, :total_passengers, :total_price)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":booking_id", $booking_id);
            $stmt->bindParam(":flight_id", $flight_id);
            $stmt->bindParam(":total_passengers", count($passengers));
            $stmt->bindParam(":total_price", $total_price);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to create booking");
            }
            
            // Insert passengers
            $passenger_query = "INSERT INTO " . $this->passengers_table . " 
                               (booking_id, first_name, last_name, email, phone) 
                               VALUES (:booking_id, :first_name, :last_name, :email, :phone)";
            
            $passenger_stmt = $this->conn->prepare($passenger_query);
            
            foreach ($passengers as $passenger) {
                $passenger_stmt->bindParam(":booking_id", $booking_id);
                $passenger_stmt->bindParam(":first_name", $passenger['first_name']);
                $passenger_stmt->bindParam(":last_name", $passenger['last_name']);
                $passenger_stmt->bindParam(":email", $passenger['email']);
                $passenger_stmt->bindParam(":phone", $passenger['phone']);
                
                if (!$passenger_stmt->execute()) {
                    throw new Exception("Failed to add passenger");
                }
            }
            
            $this->conn->commit();
            return $booking_id;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function getBookingDetails($booking_id) {
        $query = "SELECT b.*, f.*, p.first_name, p.last_name, p.email, p.phone
                  FROM " . $this->bookings_table . " b
                  JOIN flights f ON b.flight_id = f.id
                  LEFT JOIN " . $this->passengers_table . " p ON b.booking_id = p.booking_id
                  WHERE b.booking_id = :booking_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":booking_id", $booking_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>