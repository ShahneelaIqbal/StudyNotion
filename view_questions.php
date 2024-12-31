<?php
session_start();
include 'dbConnection.php';

// Check if the user is logged in and has the tutor role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'tutor') {
    header('Location: signin.php');
    exit();
}

// Ensure the database connection is valid
if (!$conn) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch all questions uploaded by students, ordered by created_at
$sql = "SELECT * FROM questions ORDER BY created_at DESC";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Questions</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('https://static.vecteezy.com/system/resources/previews/002/375/687/non_2x/school-stationary-background-free-vector.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            color: #333;
        }
        .navbar {
            background-color: #26778a;
        }
        .navbar-brand img {
            height: 40px;
        }
        .navbar-nav .nav-link {
            color: #fff;
            font-weight: bold;
        }
        .navbar-nav .nav-link:hover {
            color: #f0e68c;
        }
        .footer {
            background-color: #26778a;
            color: #fff;
            padding: 20px;
            text-align: center;
            margin-top: 20px;
        }
        .card {
            border: none;
            border-radius: 15px;
            margin-bottom: 15px;
        }
        .btn-upload-answer {
            background-color: #28a745;
            color: white;
            font-weight: bold;
        }
        .btn-upload-answer:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="logo.png" alt="Study Pool">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="TutorDashboard.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="view_questions.php">View Questions</a></li>
                <li class="nav-item"><a class="nav-link" href="upload_notes.php">Upload Notes</a></li>
                <li class="nav-item"><a class="nav-link" href="upload_book_guides.php">Upload Book Guides</a></li>
                <li class="nav-item"><a class="nav-link" href="my_uploads.php">Your Uploads</a></li>
                <li class="nav-item"><a class="nav-link" href="profile_settings.php">Profile Settings</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="signout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="mb-4 text-center">Student Questions</h2>

    <!-- Display questions -->
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Category: <?php echo htmlspecialchars($row['category']); ?></h5>
                    <p class="card-text">Question: <?php echo htmlspecialchars($row['question_text']); ?></p>
                    <p class="card-text">Budget: $<?php echo htmlspecialchars($row['budget']); ?></p>
                    <p class="card-text">Status: $<?php echo htmlspecialchars($row['status']); ?></p>
                    <p class="card-text text-muted">Posted on: <?php echo htmlspecialchars($row['created_at']); ?></p>
                    <button class="btn btn-upload-answer" onclick="uploadAnswer(<?php echo $row['id']; ?>)">Upload Answer</button>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-center">No questions uploaded yet.</p>
    <?php endif; ?>
</div>

<!-- Footer -->
<div class="footer">
    <p>&copy; 2024 Study Pool. All Rights Reserved.</p>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function uploadAnswer(questionId) {
        window.location.href = `upload_answer.php?question_id=${questionId}`;
    }
</script>
</body>
</html>
