<?php
session_start();


$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bus Ticket Booking</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
    }
    .container {
      width: 70%;
      max-width: 800px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      padding: 20px;
      margin: 20px auto;
    }
    .bus-title {
      text-align: center;
      margin-bottom: 20px;
    }
    .bus-layout {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
      margin-bottom: 20px;
    }
    .seat {
      width: 30px;
      height: 30px;
      border-radius: 5px;
      background-color: #ccc;
      cursor: pointer;
      text-align: center;
      line-height: 30px;
    }
    .seat.booked {
      background-color: #f44336;
      cursor: not-allowed;
    }
    .seat.selected {
      background-color: #4caf50;
    }
    .summary {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    button {
      padding: 10px 20px;
      background-color: #4caf50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    button:disabled {
      background-color: #ccc;
      cursor: not-allowed;
    }
  </style>
</head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Road-es</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
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

  <!-- Main Content -->
  <div class="container">
    <h1 class="bus-title">Bus Ticket Booking</h1>
    <select id="bus-selector">
      <option value="" disabled selected>Select a Bus</option>
    </select>
    <div id="bus-layout" class="bus-layout"></div>
    <div class="summary">
      <div>Total Selected: <span id="selected-count">0</span></div>
      <button onclick="window.location.href='view_cart.php'">View Cart</button>
      <button id="book-btn" disabled>Book Seats</button>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    const busSelector = document.getElementById("bus-selector");
    const busLayout = document.getElementById("bus-layout");
    const selectedCount = document.getElementById("selected-count");
    const bookBtn = document.getElementById("book-btn");

    
    fetch("fetch_buses.php")
      .then((response) => {
        if (!response.ok) throw new Error("Failed to fetch buses");
        return response.json();
      })
      .then((buses) => {
        buses.forEach((bus) => {
          const option = document.createElement("option");
          option.value = bus.bus_id;
          option.textContent = `${bus.bus_number}: ${bus.bus_name}`;
          busSelector.appendChild(option);
        });
        

        
        busSelector.addEventListener("change", (event) => {
          const selectedBusId = event.target.value;
          loadSeats(selectedBusId);
        });
      })
      .catch((error) => console.error("Error loading buses:", error));

    
    function loadSeats(bus_id) {
      fetch(`fetch_seats.php?bus_id=${bus_id}`)
        .then((response) => {
          if (!response.ok) throw new Error("Failed to fetch seats");
          return response.json();
        })
        .then((seats) => {
          busLayout.innerHTML = ""; 
          seats.forEach((seat) => {
            const seatElement = document.createElement("div");
            seatElement.className = `seat ${seat.status === "booked" ? "booked" : ""}`;
            seatElement.textContent = seat.seat_number;
            seatElement.dataset.seatId = seat.seat_id;
            if (seat.status !== "booked") {
              seatElement.addEventListener("click", () => toggleSeatSelection(seatElement));
            }
            busLayout.appendChild(seatElement);
          });
        })
        .catch((error) => console.error("Error loading seats:", error));
    }

    
    function toggleSeatSelection(seatElement) {
      seatElement.classList.toggle("selected");
      const selectedSeats = document.querySelectorAll(".seat.selected").length;
      selectedCount.textContent = selectedSeats;
      bookBtn.disabled = selectedSeats === 0;
    }

    bookBtn.addEventListener("click", () => {
      const selectedSeatElements = document.querySelectorAll(".seat.selected");
      const seatIds = Array.from(selectedSeatElements).map(
        (seat) => seat.dataset.seatId
      );
      const selectedBusId = busSelector.value;

      if (!selectedBusId || seatIds.length === 0) {
        alert("Please select a bus and at least one seat.");
        return;
      }

      fetch("book_bus_seats.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          bus_id: selectedBusId,
          seat_ids: seatIds,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            alert(data.message || "Seats successfully added to cart!");
            document.querySelectorAll(".seat.selected").forEach((seat) => {
              seat.classList.remove("selected");
            });
            selectedCount.textContent = "0";
            bookBtn.disabled = true;
            loadSeats(selectedBusId); 
          } else {
            alert("Error: " + data.error);
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("An unexpected error occurred. Please try again.");
        });
    });
  </script>
</body>
</html>
