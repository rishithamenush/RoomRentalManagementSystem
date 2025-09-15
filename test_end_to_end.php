<?php
session_start();
include 'config/db_connect.php';

echo "<h2>End-to-End Booking Test</h2>";

// Get a student user
$student = $conn->query("SELECT id, email FROM users WHERE role = 'student' LIMIT 1")->fetch_assoc();
if (!$student) {
    echo "<p style='color: red;'>No student users found.</p>";
    exit;
}

// Get a listing
$listing = $conn->query("SELECT id, title, price_lkr FROM listings LIMIT 1")->fetch_assoc();
if (!$listing) {
    echo "<p style='color: red;'>No listings found.</p>";
    exit;
}

echo "<p>Testing with student: " . $student['email'] . " (ID: " . $student['id'] . ")</p>";
echo "<p>Testing with listing: " . $listing['title'] . " (ID: " . $listing['id'] . ", Price: LKR " . $listing['price_lkr'] . ")</p>";

// Simulate login
$_SESSION['login_id'] = $student['id'];
$_SESSION['login_role'] = 'student';

// Simulate booking request
$_POST['listing_id'] = $listing['id'];
$_POST['start_date'] = '2024-12-15';
$_POST['end_date'] = '2024-12-31';
$_POST['student_note'] = 'Test booking from end-to-end test';

echo "<h3>Step 1: Making booking request via AJAX...</h3>";

// Simulate AJAX request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost/RoomRentalManagementSystem/api/ajax.php?action=request_booking");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($_POST));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIE, session_name() . '=' . session_id());

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<p>HTTP Code: " . $httpCode . "</p>";
echo "<p>Response: " . htmlspecialchars($response) . "</p>";

// Check if booking was created
$booking = $conn->query("SELECT * FROM bookings WHERE student_id = " . $student['id'] . " ORDER BY created_at DESC LIMIT 1")->fetch_assoc();

if ($booking) {
    echo "<h3>Step 2: ✅ Booking created successfully!</h3>";
    echo "<p>Booking ID: " . $booking['id'] . "</p>";
    echo "<p>Status: " . $booking['status'] . "</p>";
    echo "<p>Total Amount: LKR " . number_format($booking['total_amount'], 2) . "</p>";
    echo "<p>Start Date: " . $booking['start_date'] . "</p>";
    echo "<p>End Date: " . $booking['end_date'] . "</p>";
    
    echo "<h3>Step 3: Testing bookings display...</h3>";
    
    // Test the bookings page
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost/RoomRentalManagementSystem/pages/bookings.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIE, session_name() . '=' . session_id());
    
    $bookingsPage = curl_exec($ch);
    curl_close($ch);
    
    if (strpos($bookingsPage, $booking['id']) !== false) {
        echo "<p style='color: green;'>✅ Booking is visible in the bookings page!</p>";
    } else {
        echo "<p style='color: red;'>❌ Booking not found in bookings page.</p>";
    }
    
    // Clean up
    $conn->query("DELETE FROM bookings WHERE id = " . $booking['id']);
    echo "<p>Test booking cleaned up.</p>";
} else {
    echo "<h3>Step 2: ❌ No booking was created</h3>";
    echo "<p>Check the error logs for details.</p>";
}

// Clear session
unset($_SESSION['login_id']);
unset($_SESSION['login_role']);
?>
