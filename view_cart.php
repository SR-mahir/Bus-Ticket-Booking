<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('connection.php');


$user_id = $_SESSION['user_id'];
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user'; // Default to 'user' if role is not set

// Fetch cart items with price
$sql = "SELECT cart.cart_id, cart.bus_id, cart.seat_number, cart.price, buses.bus_name, buses.bus_number 
        FROM cart 
        JOIN buses ON cart.bus_id = buses.bus_id 
        WHERE cart.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Road-es - View Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
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
                <?php if ($role === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="admin_actions.php">Admin Dashboard</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Your Cart</h1>
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Bus Number</th>
                        <th>Bus Name</th>
                        <th>Seat Number</th>
                        <th>Price (Tk)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_price = 0; 
                    while ($row = $result->fetch_assoc()): 
                        $total_price += $row['price']; 
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['bus_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['bus_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['seat_number']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($row['price'], 2)); ?></td>
                            <td>
                                <form method="POST" action="cart.php">
                                    <input type="hidden" name="remove_item_id" value="<?php echo $row['cart_id']; ?>">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to remove this item?')">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <tr>
                        <td colspan="3" class="text-right font-weight-bold">Total Price (Tk):</td>
                        <td colspan="2" class="font-weight-bold"><?php echo htmlspecialchars(number_format($total_price, 2)); ?></td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
        <div class="d-flex justify-content-between mt-4">
            <a href="js_layout_interactive.php" class="btn btn-primary">Book More Seats</a>
            <?php if ($result->num_rows > 0): ?>
                <!-- <a href="payment.php" class="btn btn-success">Make Payment</a> -->
                <form method="POST" action="cart.php">
                    <input type="hidden" name="action" value="make_payment">
                    <button type="submit" class="btn btn-success">Make Payment</button>
                </form>
            <?php endif; ?>
            <a href="about.php" class="btn btn-primary">Leave a Review</a>
        </div>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
