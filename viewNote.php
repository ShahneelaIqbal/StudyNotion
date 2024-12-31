<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Book Guide</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff; /* White background */
            color: #333; /* Default text color */
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            background-color: #007bff; /* Blue background for navbar */
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
        .guide-content {
            white-space: pre-line; /* Preserve line breaks in content */
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

    <div class="container mt-5">
        <?php
        // Database connection
        $conn = new mysqli("localhost", "root", "", "studypool_clone");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if 'id' is set in the URL
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $note_id = intval($_GET['id']);

            // Fetch note details based on 'id'
            $sql = "SELECT * FROM notes WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $note_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                ?>
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h2>
                        <p class="card-text">
                            <strong>Description:</strong><br>
                            <?php echo nl2br(htmlspecialchars($row['content'])); ?>
                        </p>
                        <p class="text-muted">
                            <strong>Price:</strong> $<?php echo htmlspecialchars($row['fee']); ?>
                        </p>
                        <a href="payment-form-ui.php?id=<?php echo $row['id']; ?>" class="btn btn-success">Purchase Note</a>
                        <a href="noteBank.php" class="btn btn-secondary">Back to Notes</a>
                    </div>
                </div>
                <?php
            } else {
                echo "<p class='text-danger'>Note not found.</p>";
            }

            $stmt->close();
        } else {
            echo "<p class='text-danger'>Invalid Note ID.</p>";
        }

        $conn->close();
        ?>
    </div>

    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
