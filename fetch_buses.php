<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bus_ticket_booking";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT bus_id, bus_number, bus_name FROM buses";
$result = $conn->query($sql);

$buses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $buses[] = $row;
    }
}


echo json_encode($buses);

$conn->close();
?>
