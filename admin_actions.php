<?php
session_start();


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'connection.php';
$user_id = $_SESSION['user_id'];
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user'; // Default to 'user' if role is not set


$buses_query = "SELECT * FROM buses";
$buses_result = $conn->query($buses_query);

$message = ""; // To store success message


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bus_id = $_POST['bus_id'];
    $selected_seats = isset($_POST['selected_seats']) ? $_POST['selected_seats'] : [];

    // Reset all seats for the selected bus to 'available'
    $reset_query = "UPDATE busseats SET status = 'available' WHERE bus_id = ?";
    $reset_stmt = $conn->prepare($reset_query);
    $reset_stmt->bind_param('i', $bus_id);
    $reset_stmt->execute();

    // Update selected seats to 'booked'
    if (!empty($selected_seats)) {
        $update_query = "UPDATE busseats SET status = 'booked' WHERE bus_id = ? AND seat_number = ?";
        $update_stmt = $conn->prepare($update_query);

        foreach ($selected_seats as $seat) {
            $update_stmt->bind_param('is', $bus_id, $seat);
            $update_stmt->execute();
        }
    }

    $message = "Seat statuses updated successfully!";
}


function fetchSeats($conn, $bus_id) {
    $seats_query = "SELECT seat_number, status FROM busseats WHERE bus_id = ?";
    $stmt = $conn->prepare($seats_query);
    $stmt->bind_param('i', $bus_id);
    $stmt->execute();
    return $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Seat Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        .seat-layout {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-top: 20px;
        }
        .seat {
            width: 50px;
            height: 50px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            cursor: pointer;
        }
        .available {
            background-color: green;
            color: white;
        }
        .booked {
            background-color: red;
            color: white;
        }
        .selected {
            background-color: orange;
            color: white;
        }
    </style>
    <script>
        function toggleSeatSelection(seatElement) {
            seatElement.classList.toggle('selected');
            const checkbox = seatElement.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;
        }
    </script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Road-es</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">Reviews</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    
    <div class="container mt-3">
        <?php if (!empty($message)): ?>
            <div class="alert alert-success text-center" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="container my-5">
        <h1 class="text-center">Admin Dashboard</h1>
        <h3 class="text-center">Manage Bus Seats</h3>

        <?php if ($buses_result->num_rows > 0): ?>
            <?php while ($bus = $buses_result->fetch_assoc()): ?>
                <form action="admin_actions.php" method="POST">
                    <div class="my-4">
                        <h4>Bus: <?php echo htmlspecialchars($bus['bus_name']); ?> (Number: <?php echo htmlspecialchars($bus['bus_number']); ?>)</h4>
                        <input type="hidden" name="bus_id" value="<?php echo $bus['bus_id']; ?>">

                        <?php 
                        $seats_result = fetchSeats($conn, $bus['bus_id']);
                        if ($seats_result->num_rows > 0): ?>
                            <div class="seat-layout">
                                <?php while ($seat = $seats_result->fetch_assoc()): ?>
                                    <div 
                                        class="seat <?php echo $seat['status'] === 'booked' ? 'booked' : 'available'; ?>" 
                                        onclick="toggleSeatSelection(this)">
                                        <?php echo htmlspecialchars($seat['seat_number']); ?>
                                        <input type="checkbox" name="selected_seats[]" value="<?php echo htmlspecialchars($seat['seat_number']); ?>" 
                                            style="display: none;" <?php echo $seat['status'] === 'booked' ? 'checked' : ''; ?>>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p>No seats found for this bus.</p>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-primary mt-3">Update Seat Status</button>
                    </div>
                </form>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No buses found.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
