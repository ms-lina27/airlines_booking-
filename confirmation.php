<?php
require_once 'classes/Booking.php';

$booking_id = $_GET['booking_id'] ?? '';

if (empty($booking_id)) {
    header('Location: index.html');
    exit;
}

$booking = new Booking();
$booking_details = $booking->getBookingDetails($booking_id);

if (empty($booking_details)) {
    header('Location: index.html');
    exit;
}

$booking_info = $booking_details[0];
$passengers = $booking_details;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed - SkyBooker</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body>
    <header class="header">
        <div class="container">
            <a href="index.html" class="logo" style="font-size:0.3rem;">
                <span class="plane-icon" style="font-size:0.08rem;">✈️</span>
                <h1>SkyBooker</h1>
            </a>
        </div>
    </header>

    <main class="main">
        <div class="container" style="padding: 2rem 24px;">
            
            <!-- Success Message -->
            <div class="success-card fade-in-up">
                <span class="success-icon" style="font-size:0.12rem;">🎉</span>
                <h2>Booking Confirmed!</h2>
                <p>Congratulations! Your flight has been successfully booked. We've sent a detailed confirmation email to your registered address with all the important information you'll need for your journey.</p>
                <div class="booking-id-badge">
                    Booking Reference: <?php echo htmlspecialchars($booking_id); ?>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                
                <!-- Flight Details -->
                <div class="booking-form fade-in-up" style="animation-delay: 0.2s;">
                    <h3>
                        <span>✈️</span>
                        Flight Information
                    </h3>
                    
                    <div style="background: var(--secondary-color); padding: 1.5rem; border-radius: 16px; margin-bottom: 1.5rem;">
                        <h4 style="color: var(--text-primary); margin-bottom: 0.5rem; font-size: 1.25rem;">
                            <?php echo htmlspecialchars($booking_info['airline']); ?>
                        </h4>
                        <p style="color: var(--text-secondary); font-weight: 500;">
                            Flight <?php echo htmlspecialchars($booking_info['flight_number']); ?>
                        </p>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div style="text-align: center; padding: 1rem; background: var(--secondary-color); border-radius: 12px;">
                            <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em;">Departure</p>
                            <p style="font-weight: 700; font-size: 1.5rem; color: var(--text-primary); margin-bottom: 0.25rem;">
                                <?php echo date('H:i', strtotime($booking_info['departure_time'])); ?>
                            </p>
                            <p style="color: var(--text-secondary); font-weight: 500;">
                                <?php echo htmlspecialchars($booking_info['origin']); ?>
                            </p>
                        </div>
                        <div style="text-align: center; padding: 1rem; background: var(--secondary-color); border-radius: 12px;">
                            <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em;">Arrival</p>
                            <p style="font-weight: 700; font-size: 1.5rem; color: var(--text-primary); margin-bottom: 0.25rem;">
                                <?php echo date('H:i', strtotime($booking_info['arrival_time'])); ?>
                            </p>
                            <p style="color: var(--text-secondary); font-weight: 500;">
                                <?php echo htmlspecialchars($booking_info['destination']); ?>
                            </p>
                        </div>
                    </div>

                    <div style="text-align: center; padding: 1rem; background: var(--secondary-color); border-radius: 12px;">
                        <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em;">Flight Duration</p>
                        <p style="font-weight: 600; color: var(--text-primary);">
                            <?php echo htmlspecialchars($booking_info['duration']); ?>
                        </p>
                    </div>
                </div>

                <!-- Passenger Details -->
                <div class="booking-form fade-in-up" style="animation-delay: 0.3s;">
                    <h3>
                        <span>👥</span>
                        Passenger Details
                    </h3>
                    
                    <?php 
                    $passenger_count = 1;
                    $processed_passengers = [];
                    
                    foreach ($passengers as $passenger) {
                        $passenger_key = $passenger['first_name'] . $passenger['last_name'] . $passenger['email'];
                        if (!in_array($passenger_key, $processed_passengers)) {
                            $processed_passengers[] = $passenger_key;
                    ?>
                        <div style="background: var(--secondary-color); padding: 1.5rem; border-radius: 16px; margin-bottom: 1.5rem;">
                            <h4 style="color: var(--text-primary); margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;">
                                <span style="background: var(--gradient-primary); color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 600;">
                                    <?php echo $passenger_count; ?>
                                </span>
                                Passenger <?php echo $passenger_count; ?>
                            </h4>
                            <div style="display: grid; gap: 0.75rem;">
                                <p style="font-weight: 600; color: var(--text-primary); font-size: 1.1rem;">
                                    <?php echo htmlspecialchars($passenger['first_name'] . ' ' . $passenger['last_name']); ?>
                                </p>
                                <p style="color: var(--text-secondary); display: flex; align-items: center; gap: 8px;">
                                    <span>📧</span>
                                    <?php echo htmlspecialchars($passenger['email']); ?>
                                </p>
                                <p style="color: var(--text-secondary); display: flex; align-items: center; gap: 8px;">
                                    <span>📱</span>
                                    <?php echo htmlspecialchars($passenger['phone']); ?>
                                </p>
                            </div>
                        </div>
                    <?php 
                            $passenger_count++;
                        }
                    } 
                    ?>
                </div>
            </div>

            <!-- Booking Summary -->
            <div class="booking-form fade-in-up" style="animation-delay: 0.4s;">
                <h3>
                    <span>📋</span>
                    Booking Summary
                </h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">
                    <div style="space-y: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid var(--border-light);">
                            <span style="color: var(--text-secondary);">Booking Date</span>
                            <span style="font-weight: 600; color: var(--text-primary);">
                                <?php echo date('M d, Y', strtotime($booking_info['booking_date'])); ?>
                            </span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid var(--border-light);">
                            <span style="color: var(--text-secondary);">Status</span>
                            <span style="background: var(--gradient-success); color: white; padding: 0.375rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                <?php echo ucfirst($booking_info['booking_status']); ?>
                            </span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0;">
                            <span style="color: var(--text-secondary);">Total Passengers</span>
                            <span style="font-weight: 600; color: var(--text-primary);">
                                <?php echo $booking_info['total_passengers']; ?>
                            </span>
                        </div>
                    </div>
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid var(--border-light);">
                            <span style="color: var(--text-secondary);">Price per person</span>
                            <span style="font-weight: 600; color: var(--text-primary);">
                                $<?php echo number_format($booking_info['price'], 2); ?>
                            </span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 0; background: var(--secondary-color); margin: 1rem -1.5rem 0; padding-left: 1.5rem; padding-right: 1.5rem; border-radius: 12px;">
                            <span style="font-weight: 600; font-size: 1.25rem; color: var(--text-primary);">Total Amount Paid</span>
                            <span style="font-weight: 700; font-size: 1.5rem; background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                $<?php echo number_format($booking_info['total_price'], 2); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons fade-in-up" style="animation-delay: 0.5s;">
                <button class="action-btn" onclick="window.print()">
                    <span>📄</span>
                    Download Ticket
                </button>
                <button class="action-btn" onclick="alert('Confirmation email sent!')">
                    <span>📧</span>
                    Email Confirmation
                </button>
                <a href="index.html" class="action-btn primary">
                    <span>🚀</span>
                    Book Another Flight
                </a>
            </div>
        </div>
    </main>

    <script src="js/script.js"></script>
</body>
</html>