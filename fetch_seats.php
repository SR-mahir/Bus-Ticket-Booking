<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bus_ticket_booking";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$bus_id = isset($_GET['bus_id']) ? intval($_GET['bus_id']) : 0;

$sql = "SELECT seat_id, seat_number, status FROM busseats WHERE bus_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bus_id);
$stmt->execute();
$result = $stmt->get_result();

$seats = [];
while ($row = $result->fetch_assoc()) {
    $seats[] = $row;
}

echo json_encode($seats);

$conn->close();
?>
