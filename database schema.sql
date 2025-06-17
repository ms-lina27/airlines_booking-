-- Create database
CREATE DATABASE airline_booking;
USE airline_booking;

-- Flights table
CREATE TABLE flights (
    id INT PRIMARY KEY AUTO_INCREMENT,
    airline VARCHAR(100) NOT NULL,
    flight_number VARCHAR(20) NOT NULL,
    origin VARCHAR(100) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    departure_time TIME NOT NULL,
    arrival_time TIME NOT NULL,
    departure_date DATE NOT NULL,
    duration VARCHAR(20) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    available_seats INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bookings table
CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id VARCHAR(20) UNIQUE NOT NULL,
    flight_id INT NOT NULL,
    total_passengers INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    booking_status ENUM('confirmed', 'cancelled', 'pending') DEFAULT 'confirmed',
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (flight_id) REFERENCES flights(id)
);

-- Passengers table
CREATE TABLE passengers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id VARCHAR(20) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id)
);

-- Insert sample flight data
INSERT INTO flights (airline, flight_number, origin, destination, departure_time, arrival_time, departure_date, duration, price, available_seats) VALUES
('SkyWings Airlines', 'SW101', 'New York', 'Los Angeles', '08:00:00', '11:30:00', '2024-07-01', '5h 30m', 299.00, 45),
('CloudJet', 'CJ205', 'New York', 'Los Angeles', '14:15:00', '17:45:00', '2024-07-01', '5h 30m', 349.00, 23),
('AeroFast', 'AF789', 'New York', 'Los Angeles', '19:30:00', '23:00:00', '2024-07-01', '5h 30m', 279.00, 67),
('SkyWings Airlines', 'SW302', 'Los Angeles', 'New York', '09:15:00', '17:45:00', '2024-07-01', '5h 30m', 319.00, 34),
('CloudJet', 'CJ456', 'Chicago', 'Miami', '11:00:00', '14:30:00', '2024-07-01', '3h 30m', 189.00, 56),
('AeroFast', 'AF123', 'Miami', 'Chicago', '16:20:00', '18:50:00', '2024-07-01', '3h 30m', 199.00, 42);