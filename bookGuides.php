<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Guides</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff; /* White background for the entire page */
            color: #333; /* Default text color */
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            background-color: #007bff; /* Blue background for the navbar */
            padding: 10px 20px;
            align-items: center;
        }
        .navbar img {
            height: 50px;
        }
        .navbar input[type="text"] {
            padding: 10px;
            width: 300px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .menu-icon {
            display: none;
            font-size: 30px;
            color: white;
            cursor: pointer;
        }
        .menu {
            display: flex;
            gap: 20px;
        }
        .menu a {
            color: white;
            text-decoration: none;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add slight shadow for cards */
            border: none; /* Remove default border */
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="dashboard.php">
            <img src="https://www.studypool.com/images/logo.png" width="150px" alt="Study Pool Logo">
        </a>
        <span class="menu-icon" onclick="toggleMenu()">&#9776;</span>
        <div class="menu">
            <a href="myProfile.php">My Profile</a>
            <a href="postQuestion.php">Post a Question</a>
            <a href="noteBank.php">Notebank</a>
            <a href="bookGuides.php">Book Guides</a>
            <a href="howItWorks.php">How It Works</a>
            <a href="myTutors.php">Tutors</a>
            <a href="settings.php">Settings</a>
            <a href="signout.php">Logout</a>
        </div>
    </div>

    <!-- Book Guides Section -->
    <div class="container mt-5">
        <h2 class="mb-4">Available Book Guides</h2>
        <div class="row">
            <?php
            // Database connection
            $conn = new mysqli("localhost", "root", "", "studypool_clone");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch book guides from the database
            $sql = "SELECT * FROM book_guides";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars(substr($row['description'], 0, 100)) . '...'; ?></p>
                                <p class="text-muted">Price: $<?php echo htmlspecialchars($row['price']); ?></p>
                                <a href="viewBookGuide.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">View Guide</a>
                                <a href="payment-form-ui.php?id=<?php echo $row['id']; ?>" class="btn btn-success">Purchase</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='text-center'>No book guides available.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>

    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
