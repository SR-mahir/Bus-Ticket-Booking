<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Road-es</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Road-es</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="js_layout_interactive.php">Buy Ticket</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">Reviews</a></li>
                <?php if ($role === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="admin_actions.php">Admin Dashboard</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>
    <div id="demo" class="carousel slide" data-ride="carousel">
  <ul class="carousel-indicators">
    <li data-target="#demo" data-slide-to="0" class="active"></li>
    <li data-target="#demo" data-slide-to="1"></li>
    <li data-target="#demo" data-slide-to="2"></li>
  </ul>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="images/pic_1.jpg" alt="Saree" width="100%" height="700vh">
      <div class="carousel-caption">
        <h1>Bracu Bus Service</h1>
        <p>Safe Travels For Students</p>
      </div>   
    </div>
    <div class="carousel-item">
      <img src="images/pic_2.jpg" alt="Panjabi" width="100%" height="700vh">
      <div class="carousel-caption">
        <h1>Bracu Bus Service</h1>
        <p>Safe Travels For Students</p>
      </div>   
    </div>
    <div class="carousel-item">
      <img src="images/pic_3.jpg" alt="Urban Drips" width="100%" height="700vh">
      <div class="carousel-caption">
        <h1>Bracu Bus Service</h1>
        <p>Safe Travels For Students</p>
      </div>   
    </div>
  </div>
  <a class="carousel-control-prev" href="#demo" data-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </a>
  <a class="carousel-control-next" href="#demo" data-slide="next">
    <span class="carousel-control-next-icon"></span>
  </a>
</div>
<section class = "my-5">
  <div class ="py-2">
    <h1 class = "text-center">About Us</h1>
  </div>
  <div class = "container-fluid">
    <div class = "row">
      <div class="col-lg-6 col-md-6 col-12">
        <img src = "Images/Logo.png" class = "img-fluid">
      </div>
      <div class="col-lg-6 col-md-6 col-12">
        <h3 class = "text-center">BracU Bus Services</h3>
        <p class="text-left about-text">At this point the university is providing buses for <b>8:00 AM</b> classes from <b>Gulshan</b> to <b>Badda</b>. More Schedule and timing will be added soon.
        </p>
        <div>
        <a href = "about.php" class = "btn btn-success">Give A review<a>
        </div>
      </div>

    </div>
  </div>
</section>



    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
