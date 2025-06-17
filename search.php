<?php
require_once 'classes/Flight.php';

$origin = $_GET['origin'] ?? '';
$destination = $_GET['destination'] ?? '';
$departure_date = $_GET['departure_date'] ?? '';
$passengers = $_GET['passengers'] ?? 1;

$flight = new Flight();
$flights = $flight->searchFlights($origin, $destination, $departure_date);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - SkyBooker</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo" style="font-size:0.3rem;">
                <span class="plane-icon" style="font-size:0.08rem;">✈️</span>
                <h1>SkyBooker</h1>
            </div>
            <a href="index.html" class="search-btn" style="padding: 0.5rem 1rem; text-decoration: none; font-size: 0.9rem;">New Search</a>
        </div>
    </header>

    <main class="main">
        <div class="container" style="padding: 2rem 20px;">
            <!-- Search Summary -->
            <div class="search-card" style="margin-bottom: 2rem;">
                <h2>Flight Search Results</h2>
                <p><?php echo htmlspecialchars($origin); ?> → <?php echo htmlspecialchars($destination); ?> • <?php echo htmlspecialchars($departure_date); ?> • <?php echo $passengers; ?> passenger<?php echo $passengers > 1 ? 's' : ''; ?></p>
            </div>

            <!-- Flight Results -->
            <?php if (empty($flights)): ?>
                <div class="search-card" style="text-align: center; padding: 3rem;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">✈️</div>
                    <h3>No flights found</h3>
                    <p style="margin: 1rem 0;">Sorry, we couldn't find any flights for your search criteria.</p>
                    <a href="index.html" class="search-btn" style="display: inline-block; text-decoration: none;">Try a different search</a>
                </div>
            <?php else: ?>
                <?php foreach ($flights as $flight_data): ?>
                    <div class="flight-card">
                        <div class="flight-header">
                            <div class="airline-info">
                                <h3><?php echo htmlspecialchars($flight_data['airline']); ?></h3>
                                <div class="flight-number"><?php echo htmlspecialchars($flight_data['flight_number']); ?></div>
                            </div>
                            <div class="seats-badge"><?php echo $flight_data['available_seats']; ?> seats left</div>
                        </div>

                        <div class="flight-details">
                            <div class="time-info">
                                <div class="time"><?php echo date('H:i', strtotime($flight_data['departure_time'])); ?></div>
                                <div class="city"><?php echo htmlspecialchars($flight_data['origin']); ?></div>
                            </div>

                            <div class="duration-info">
                                <div class="arrow">→</div>
                                <div style="font-size: 0.8rem; margin-top: 0.25rem;">
                                    🕒 <?php echo htmlspecialchars($flight_data['duration']); ?>
                                </div>
                            </div>

                            <div class="time-info">
                                <div class="time"><?php echo date('H:i', strtotime($flight_data['arrival_time'])); ?></div>
                                <div class="city"><?php echo htmlspecialchars($flight_data['destination']); ?></div>
                            </div>

                            <div class="price-section">
                                <div class="price">$<?php echo number_format($flight_data['price'], 2); ?></div>
                                <div class="per-person">per person</div>
                                <button class="select-btn" onclick="selectFlight(<?php echo $flight_data['id']; ?>, <?php echo $passengers; ?>)">
                                    Select Flight
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <script src="js/script.js"></script>
</body>
</html>