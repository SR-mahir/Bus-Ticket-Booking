<?php
session_start();
include 'connection.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : null;
    $comment = isset($_POST['comment']) ? htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8') : '';

    if ($rating < 1 || $rating > 5 || empty($comment)) {
        $_SESSION['message'] = "<div class='alert alert-danger text-center'>Invalid rating or comment. Please try again.</div>";
    } else {
       
        $query = "INSERT INTO reviews (user_id, rating, comment) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param('iis', $user_id, $rating, $comment);

        if ($stmt->execute()) {
            $_SESSION['message'] = "<div class='alert alert-success text-center'>Thank you for your review!</div>";
        } else {
            $_SESSION['message'] = "<div class='alert alert-danger text-center'>Error submitting your review. Please try again.</div>";
        }
        $stmt->close();
    }

    
    header("Location: about.php");
    exit();
}


$reviews_query = "SELECT reviews.rating, reviews.comment, users.username 
                  FROM reviews 
                  JOIN users ON reviews.user_id = users.id";
$reviews_result = $conn->query($reviews_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ratings and Reviews</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        .star {
            color: gold;
            font-size: 24px;
        }
        .review-box {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
    </style>
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

   
    <?php if (isset($_SESSION['message'])): ?>
        <div class="container mt-3">
            <?php 
            echo $_SESSION['message']; 
            unset($_SESSION['message']); 
            ?>
        </div>
    <?php endif; ?>

    <div class="container my-5">
        <h1 class="text-center">Ratings and Reviews</h1>

        <!-- Review Form -->
        <div class="my-4">
            <h3>Submit Your Review</h3>
            <form action="about.php" method="POST">
                <div class="form-group">
                    <label for="rating">Rating (1-5):</label>
                    <select name="rating" id="rating" class="form-control" required>
                        <option value="" disabled selected>Choose a rating</option>
                        <option value="1">1 Star</option>
                        <option value="2">2 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="5">5 Stars</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="comment">Comment:</label>
                    <textarea name="comment" id="comment" rows="4" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Review</button>
            </form>
        </div>

       
        <div class="my-4">
            <h3>User Reviews</h3>
            <?php if ($reviews_result->num_rows > 0): ?>
                <?php while ($review = $reviews_result->fetch_assoc()): ?>
                    <div class="review-box">
                        <h5><strong><?php echo htmlspecialchars($review['username']); ?></strong></h5>
                        <p>
                            <?php for ($i = 0; $i < $review['rating']; $i++): ?>
                                <span class="star">&#9733;</span>
                            <?php endfor; ?>
                        </p>
                        <p><?php echo htmlspecialchars($review['comment']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No reviews yet. Be the first to submit one!</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
