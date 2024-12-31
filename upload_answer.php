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

// Fetch the question ID from the GET parameter
$question_id = isset($_GET['question_id']) ? intval($_GET['question_id']) : 0;
if ($question_id <= 0) {
    echo "<script>alert('Invalid question ID.'); window.location.href='view_questions.php';</script>";
    exit();
}

// Fetch the question details
$sql = "SELECT * FROM questions WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $question_id);
$stmt->execute();
$question = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$question) {
    echo "<script>alert('Question not found.'); window.location.href='view_questions.php';</script>";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answer_text = trim($_POST['answer_text']);
    $attachment_url = '';

    // Handle file upload
    if (isset($_FILES['attachment']['name']) && $_FILES['attachment']['error'] === 0) {
        $upload_dir = 'uploads/';
        $attachment_url = $upload_dir . basename($_FILES['attachment']['name']);
        if (!move_uploaded_file($_FILES['attachment']['tmp_name'], $attachment_url)) {
            $attachment_url = '';
        }
    }

    // Save the answer
    $user_id = $_SESSION['user_id'];  // Assuming user_id is the tutor's ID
    $sql = "INSERT INTO answers (question_id, tutor_id, answer_text, attachment_url, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $question_id, $user_id, $answer_text, $attachment_url);

    if ($stmt->execute()) {
        echo "<script>alert('Answer uploaded successfully.'); window.location.href='view_questions.php';</script>";
    } else {
        echo "<script>alert('Error uploading answer. Please try again.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Answer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('https://img.freepik.com/premium-photo/different-office-supplies-colored-stickers-notebooks-pens-pencils-rullers-marckers-different-school-items-important-information-accessories-lying-desk_424947-10952.jpg?semt=ais_hybrid');
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
                <h2 class="text-center mb-4">Upload Answer</h2>
                <p><strong>Question:</strong> <?php echo htmlspecialchars($question['question_text']); ?></p>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($question['category']); ?></p>
                <p><strong>Budget:</strong> $<?php echo htmlspecialchars($question['budget']); ?></p>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="answer_text" class="form-label">Answer</label>
                        <textarea class="form-control" id="answer_text" name="answer_text" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="attachment" class="form-label">Attachment (optional)</label>
                        <input type="file" class="form-control" id="attachment" name="attachment">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Submit Answer</button>
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
