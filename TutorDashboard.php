<?php
session_start();
include 'dbConnection.php';

// Check if the user is logged in and has the tutor role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'tutor') {
    header('Location: signin.php');
    exit();
}

// Get the logged-in user's email
$email = $_SESSION['email'];

// Ensure the database connection is valid
if (!$conn) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch tutor data from the database
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the tutor's data
$tutor = $result->fetch_assoc();

// If no tutor is found, redirect to the login page
if (!$tutor) {
    header("Location: signin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('https://i.pinimg.com/564x/f8/29/70/f82970f3e1d28d3e816729d850b2b1a0.jpg'); /* Replace with the path to your image */
            background-size: cover; /* Ensures the image covers the entire background */
            background-repeat: no-repeat; /* Prevents tiling */
            background-position: center; /* Centers the image */
            background-attachment: fixed; /* Keeps the image fixed when scrolling */
            color: #333; /* Ensures text is readable against the background */
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
        .dashboard-container {
            margin-top: 20px;
        }
        .card {
            border: none;
            border-radius: 15px;
        }
        .card:hover {
            transform: scale(1.02);
            transition: 0.3s;
        }
        .footer {
            background-color: #26778a;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        .btn-primary {
            background-color: #0069d9;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
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
                <li class="nav-item"><a class="nav-link text-danger" href="signout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container dashboard-container">
    <!-- Welcome Message -->
    <div class="card shadow-sm mb-4">
        <div class="card-body text-center">
            <h3 class="card-title">Welcome, <?php echo htmlspecialchars($tutor['email']); ?>!</h3>
            <p>We are thrilled to have you as part of the Study Pool tutor community!</p>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Your Profile Information</h5>
            <ul class="list-group">
                <li class="list-group-item">Email: <?php echo htmlspecialchars($tutor['email']); ?></li>
                <li class="list-group-item">Verification Status: 
                    <span class="badge bg-<?php echo ($tutor['verification_status'] === 'approved') ? 'success' : 'danger'; ?>">
                        <?php echo ucfirst(htmlspecialchars($tutor['verification_status'])); ?>
                    </span>
                </li>
                <li class="list-group-item">
                    Profile Photo:
                    <?php if (!empty($tutor['photo_path'])): ?>
                        <img src="<?php echo htmlspecialchars($tutor['photo_path']); ?>" alt="Profile Photo" style="height: 50px;">
                    <?php else: ?>
                        <span class="text-muted">No photo available</span>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>

    <!-- About Study Pool -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">About Study Pool</h5>
            <p>Study Pool is a revolutionary online platform that bridges the gap between academic challenges and solutions. We empower tutors and students to create a collaborative learning environment where knowledge flows effortlessly. Join us in shaping the future of education.</p>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">What Students Say</h5>
            <p>“Study Pool tutors are lifesavers! Their expertise and dedication made a huge difference in my grades.” – Sarah K.</p>
            <p>“The best platform for learning. Highly recommended for anyone struggling academically.” – James T.</p>
        </div>
    </div>

    <!-- Resources Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Educational Resources</h5>
            <p>Gain access to an extensive library of academic resources, ranging from detailed study guides to interactive learning tools, and stay ahead in your tutoring journey.</p>
            <a href="#" class="btn btn-primary">Explore Resources</a>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p>&copy; 2024 Study Pool. All Rights Reserved.</p>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
