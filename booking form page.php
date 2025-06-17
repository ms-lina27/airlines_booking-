<?php
require_once 'classes/Flight.php';

$flight_id = $_GET['flight_id'] ?? 0;
$passengers = $_GET['passengers'] ?? 1;

$flight = new Flight();
$flight_data = $flight->getFlightById($flight_id);

if (!$flight_data) {
    header('Location: index.html');
    exit;
}

$total_price = $flight_data['price'] * $passengers;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - SkyBooker</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo" style="font-size:0.3rem;">
                <span class="plane-icon" style="font-size:0.08rem;">✈️</span>
                <h1>SkyBooker</h1>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container" style="padding: 2rem 20px;">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
                
                <!-- Booking Form -->
                <div>
                    <form action="process_booking.php" method="POST" onsubmit="return validateBookingForm()">
                        <input type="hidden" name="flight_id" value="<?php echo $flight_id; ?>">
                        <input type="hidden" name="total_passengers" value="<?php echo $passengers; ?>">
                        <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">

                        <!-- Passenger Information -->
                        <div class="booking-form">
                            <h3>👤 Passenger Information</h3>
                            
                            <?php for ($i = 1; $i <= $passengers; $i++): ?>
                                <div class="passenger-section">
                                    <h4>Passenger <?php echo $i; ?></h4>
                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label for="passenger_<?php echo $i; ?>_first_name">First Name</label>
                                            <input type="text" id="passenger_<?php echo $i; ?>_first_name" 
                                                   name="passenger_<?php echo $i; ?>_first_name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="passenger_<?php echo $i; ?>_last_name">Last Name</label>
                                            <input type="text" id="passenger_<?php echo $i; ?>_last_name" 
                                                   name="passenger_<?php echo $i; ?>_last_name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="passenger_<?php echo $i; ?>_email">Email</label>
                                            <input type="email" id="passenger_<?php echo $i; ?>_email" 
                                                   name="passenger_<?php echo $i; ?>_email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="passenger_<?php echo $i; ?>_phone">Phone</label>
                                            <input type="tel" id="passenger_<?php echo $i; ?>_phone" 
                                                   name="passenger_<?php echo $i; ?>_phone" required>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>

                        <!-- Payment Information -->
                        <div class="booking-form">
                            <h3>💳 Payment Information</h3>
                            <div class="form-grid">
                                <div class="form-group" style="grid-column: 1 / -1;">
                                    <label for="cardholder_name">Cardholder Name</label>
                                    <input type="text" id="cardholder_name" name="cardholder_name" required>
                                </div>
                                <div class="form-group" style="grid-column: 1 / -1;">
                                    <label for="card_number">Card Number</label>
                                    <input type="text" id="card_number" name="card_number" 
                                           placeholder="1234 5678 9012 3456" required>
                                </div>
                                <div class="form-group">
                                    <label for="expiry_date">Expiry Date</label>
                                    <input type="text" id="expiry_date" name="expiry_date" 
                                           placeholder="MM/YY" required>
                                </div>
                                <div class="form-group">
                                    <label for="cvv">CVV</label>
                                    <input type="text" id="cvv" name="cvv" placeholder="123" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="search-btn" style="width: 100%; font-size: 1.1rem;">
                            Complete Booking - $<?php echo number_format($total_price, 2); ?>
                        </button>
                    </form>
                </div>

                <!-- Flight Summary -->
                <div>
                    <div class="booking-form" style="position: sticky; top: 2rem;">
                        <h3>Flight Summary</h3>
                        
                        <div style="margin-bottom: 1rem;">
                            <h4><?php echo htmlspecialchars($flight_data['airline']); ?></h4>
                            <p style="color: #6b7280;"><?php echo htmlspecialchars($flight_data['flight_number']); ?></p>
                        </div>

                        <hr style="margin: 1rem 0; border: none; border-top: 1px solid #e5e7eb;">

                        <div style="margin-bottom: 1rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span style="font-weight: 600;"><?php echo date('H:i', strtotime($flight_data['departure_time'])); ?></span>
                                <span style="font-weight: 600;"><?php echo date('H:i', strtotime($flight_data['arrival_time'])); ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; color: #6b7280;">
                                <span><?php echo htmlspecialchars($flight_data['origin']); ?></span>
                                <span><?php echo htmlspecialchars($flight_data['destination']); ?></span>
                            </div>
                            <p style="text-align: center; color: #6b7280; margin-top: 0.5rem;">
                                Duration: <?php echo htmlspecialchars($flight_data['duration']); ?>
                            </p>
                        </div>

                        <hr style="margin: 1rem 0; border: none; border-top: 1px solid #e5e7eb;">

                        <div style="margin-bottom: 1rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span>Price per person:</span>
                                <span>$<?php echo number_format($flight_data['price'], 2); ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span>Passengers:</span>
                                <span><?php echo $passengers; ?></span>
                            </div>
                            <hr style="margin: 0.5rem 0; border: none; border-top: 1px solid #e5e7eb;">
                            <div style="display: flex; justify-content: space-between; font-weight: 600; font-size: 1.2rem;">
                                <span>Total:</span>
                                <span>$<?php echo number_format($total_price, 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="js/script.js"></script>
</body>
</html>