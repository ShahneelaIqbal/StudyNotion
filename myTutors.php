<?php
// Include database connection file
include 'dbConnection.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];  // Get logged-in user ID

// Fetch tutors (users with the 'tutor' role)
$tutors_query = "SELECT id, username, email, created_at 
                 FROM users 
                 WHERE role = 'tutor'";
$tutors_result = $conn->query($tutors_query);

// Check if there are any tutors
if ($tutors_result->num_rows == 0) {
    $no_tutors = "No tutors available at the moment.";
} else {
    $no_tutors = "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutors - StudyPool</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Styling for the tutors page */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            background-color: #007bff;
            padding: 10px 20px;
            align-items: center;
        }
        .navbar img {
            height: 50px;
        }
        .menu {
            display: flex;
            gap: 20px;
        }
        .menu a {
            color: white;
            text-decoration: none;
        }
        .tutor-list {
            padding: 20px;
        }
        .tutor-card {
            display: flex;
            justify-content: space-between;
            background-color: #fff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #007bff;
            color: white;
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

<!-- My Tutors -->
<div class="tutor-list">
    <h2>My Tutors</h2>

    <!-- If no tutors, show message -->
    <?php if ($no_tutors != ""): ?>
        <p><?php echo $no_tutors; ?></p>
    <?php else: ?>
        <!-- Loop through the tutors and display them -->
        <?php while ($row = $tutors_result->fetch_assoc()): ?>
            <div class="tutor-card">
                <div>
                    <strong><?php echo $row['username']; ?></strong>
                    <p>Joined: <?php echo $row['created_at']; ?></p>
                </div>
                <div>
                    <a href="tutorProfile.php?id=<?php echo $row['id']; ?>">View Profile</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<!-- Footer -->
<div class="footer">
    <p>&copy; 2024 Study Pool</p>
</div>

</body>
</html>
