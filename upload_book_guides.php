<?php
session_start();
include 'dbConnection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

// Ensure the database connection is valid
if (!$conn) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get the logged-in user (tutor) ID from the session
$tutor_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guide_title = trim($_POST['title']);
    $guide_description = trim($_POST['description']);
    $price = $_POST['price'];

    // Handle file upload
    $attachment_url = "";
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $upload_dir = 'uploads/guides/';
        $file_name = basename($_FILES['attachment']['name']);
        $file_path = $upload_dir . $file_name;

        // Ensure the directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Move the uploaded file to the desired location
        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $file_path)) {
            $attachment_url = $file_path;
        } else {
            echo "<script>alert('Error uploading the file.');</script>";
        }
    }

    // Insert the book guide into the database, including tutor_id
    $sql = "INSERT INTO book_guides (title, description, price, attachment_url, tutor_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsd", $guide_title, $guide_description, $price, $attachment_url, $tutor_id);

    if ($stmt->execute()) {
        echo "<script>alert('Book guide uploaded successfully.'); window.location.href='TutorDashboard.php';</script>";
    } else {
        echo "<script>alert('Error uploading book guide. Please try again.');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Book Guide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('images/background.jpg');
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
        .form-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            min-height: 100vh;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="logo.png" alt="Study Pool">
        </a>
        <div class="collapse navbar-collapse">
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
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-container">
                <h2 class="text-center mb-4">Upload Book Guide</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="attachment" class="form-label">Attachment</label>
                        <input type="file" class="form-control" id="attachment" name="attachment" accept=".pdf,.docx,.pptx,.txt">
                    </div>
                    <button type="submit" class="btn" style="background-color: #26778a; color: #fff; width: 100%;">Submit Guide</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="footer">
    <p>&copy; 2024 Study Pool. All Rights Reserved.</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
