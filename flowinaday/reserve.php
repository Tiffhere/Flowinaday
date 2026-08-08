<?php
// Eorror check
ini_set('display_errors', 0); 
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

try {
    include "db_open.php";

    // check login state
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        echo json_encode(['success' => false, 'message' => 'Please log in']);
        exit;
    }

    if (!isset($_SESSION['userid'])) {
        echo json_encode(['success' => false, 'message' => 'User ID not found']);
        exit;
    }

    // POST receive
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);
    
    if (!$data || !isset($data['classid'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid data received']);
        exit;
    }

    $classid = intval($data['classid']);
    $userid = $_SESSION['userid'];

    if ($classid <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid class ID']);
        exit;
    }

    // reserve check
    $sql_check = "SELECT * FROM bookings WHERE userid = ? AND classid = ?";
    $stmt = $dbc->prepare($sql_check);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database prepare error: ' . $dbc->error]);
        exit;
    }
    
    $stmt->bind_param("ii", $userid, $classid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'You have already booked this class.']);
        exit;
    }

    // add reservation
    $sql_insert = "INSERT INTO bookings (userid, classid, booking_time) VALUES (?, ?, NOW())";
    $stmt = $dbc->prepare($sql_insert);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database prepare error: ' . $dbc->error]);
        exit;
    }
    
    $stmt->bind_param("ii", $userid, $classid);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Booking successful']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    }

    $stmt->close();
    $dbc->close();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
