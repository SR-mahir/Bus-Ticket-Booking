<?php
session_start();
header('Content-Type: application/json');


if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in.']);
    exit;
}

include('connection.php');


$data = json_decode(file_get_contents('php://input'), true);


if (!isset($data['bus_id']) || empty($data['bus_id']) || !isset($data['seat_ids']) || empty($data['seat_ids'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid input data.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$bus_id = $data['bus_id'];
$seat_ids = $data['seat_ids'];

$conn->begin_transaction(); 

try {
    foreach ($seat_ids as $seat_id) {
        // Check if the seat is available
        $seat_query = $conn->prepare("SELECT seat_number FROM busseats WHERE seat_id = ? AND status = 'available'");
        $seat_query->bind_param("i", $seat_id);
        $seat_query->execute();
        $seat_result = $seat_query->get_result();

        if ($seat_result->num_rows === 0) {
            throw new Exception("Seat with ID $seat_id is not available."); 
        }

        $seat_row = $seat_result->fetch_assoc();
        $seat_number = $seat_row['seat_number'];

        // Add the seat to the user's cart
        $insert_query = $conn->prepare("INSERT INTO cart (user_id, bus_id, seat_number) VALUES (?, ?, ?)");
        $insert_query->bind_param("iis", $user_id, $bus_id, $seat_number);
        $insert_query->execute();

        // Mark the seat as booked
        $update_query = $conn->prepare("UPDATE busseats SET status = 'booked' WHERE seat_id = ?");
        $update_query->bind_param("i", $seat_id);
        $update_query->execute();
    }

    $conn->commit(); 
    echo json_encode(['success' => true, 'message' => 'Seats successfully added to cart!']);
} catch (Exception $e) {
    $conn->rollback(); 
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
