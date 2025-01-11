<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('connection.php');

$user_id = $_SESSION['user_id']; 


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item_id'])) {
    $cart_id = intval($_POST['remove_item_id']); 

    
    $conn->begin_transaction();

    try {
        
        $seat_query = "SELECT bus_id, seat_number FROM cart WHERE cart_id = ?";
        $seat_stmt = $conn->prepare($seat_query);
        $seat_stmt->bind_param("i", $cart_id);
        $seat_stmt->execute();
        $seat_result = $seat_stmt->get_result();

        if ($seat_result->num_rows > 0) {
            $seat_row = $seat_result->fetch_assoc();
            $bus_id = $seat_row['bus_id'];
            $seat_number = $seat_row['seat_number'];

            
            $delete_sql = "DELETE FROM cart WHERE cart_id = ? AND user_id = ?";
            $stmt = $conn->prepare($delete_sql);
            $stmt->bind_param("ii", $cart_id, $user_id);
            $stmt->execute();

            
            $update_seat_sql = "UPDATE busseats SET status = 'available' WHERE bus_id = ? AND seat_number = ?";
            $update_stmt = $conn->prepare($update_seat_sql);
            $update_stmt->bind_param("is", $bus_id, $seat_number);
            $update_stmt->execute();

            // Commit the transaction
            $conn->commit();
            $_SESSION['message'] = "Item removed successfully.";
        } else {
            throw new Exception("Item not found in the cart.");
        }
    } catch (Exception $e) {
        
        $conn->rollback();
        $_SESSION['message'] = "Error: " . $e->getMessage();
    }

    
    header("Location: view_cart.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'make_payment') {
    
    $conn->begin_transaction();

    try {
        
        $seat_query = "SELECT bus_id, seat_number FROM cart WHERE user_id = ?";
        $seat_stmt = $conn->prepare($seat_query);
        $seat_stmt->bind_param("i", $user_id);
        $seat_stmt->execute();
        $seat_result = $seat_stmt->get_result();

        
        while ($seat_row = $seat_result->fetch_assoc()) {
            $bus_id = $seat_row['bus_id'];
            $seat_number = $seat_row['seat_number'];

            $update_seat_sql = "UPDATE busseats SET status = 'available' WHERE bus_id = ? AND seat_number = ?";
            $update_stmt = $conn->prepare($update_seat_sql);
            $update_stmt->bind_param("is", $bus_id, $seat_number);
            $update_stmt->execute();
        }

        
        $delete_all_sql = "DELETE FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($delete_all_sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        
        $conn->commit();

        
        header("Location: payment.php");
        exit();
    } catch (Exception $e) {
       
        $conn->rollback();
        $_SESSION['message'] = "Error: " . $e->getMessage();
        header("Location: view_cart.php");
        exit();
    }
}
?>
