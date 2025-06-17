<?php
require_once 'classes/Booking.php';
require_once 'classes/Flight.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

$flight_id = $_POST['flight_id'];
$total_passengers = $_POST['total_passengers'];
$total_price = $_POST['total_price'];

// Collect passenger data
$passengers = [];
for ($i = 1; $i <= $total_passengers; $i++) {
    $passengers[] = [
        'first_name' => $_POST["passenger_{$i}_first_name"],
        'last_name' => $_POST["passenger_{$i}_last_name"],
        'email' => $_POST["passenger_{$i}_email"],
        'phone' => $_POST["passenger_{$i}_phone"]
    ];
}

// Validate required fields
$required_fields = ['flight_id', 'total_passengers', 'total_price'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        die('Missing required field: ' . $field);
    }
}

// Validate passenger data
foreach ($passengers as $passenger) {
    foreach ($passenger as $field => $value) {
        if (empty($value)) {
            die('Missing passenger information');
        }
    }
}

try {
    // Create booking
    $booking = new Booking();
    $booking_id = $booking->createBooking($flight_id, $passengers, $total_price);
    
    if ($booking_id) {
        // Update available seats
        $flight = new Flight();
        $flight->updateAvailableSeats($flight_id, $total_passengers);
        
        // Redirect to confirmation page
        header("Location: confirmation.php?booking_id=" . $booking_id);
        exit;
    } else {
        throw new Exception('Failed to create booking');
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    echo '<br><a href="index.html">Return to home</a>';
}
?>