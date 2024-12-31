<?php
session_start();
include('dbconnection.php'); // Include the database connection

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect if user is not logged in
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID
$role = $_SESSION['role']; // Get the user's role (either 'student' or 'tutor')

// Fetch user profile information
if ($role == 'student') {
    $sql = "SELECT id, email, username, created_at, verification_status FROM users WHERE id = ?";
} elseif ($role == 'tutor') {
    $sql = "SELECT id, email, first_name, last_name, photo_path, bio, phone_number, location, subject_specialization, years_of_experience, education_level, created_at, verification_status FROM tutors WHERE id = ?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if user data was fetched
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // Handle error if no data is returned
    echo "Error: Profile data not found.";
    exit();
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($role == 'student') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $verification_status = $_POST['verification_status'];

        // Update user profile
        $update_sql = "UPDATE users SET username = ?, email = ?, verification_status = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssi", $username, $email, $verification_status, $user_id);
        $update_stmt->execute();

        $_SESSION['message'] = "Profile updated successfully!";
    } elseif ($role == 'tutor') {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $bio = $_POST['bio'];
        $phone_number = $_POST['phone_number'];
        $location = $_POST['location'];
        $subject_specialization = $_POST['subject_specialization'];
        $years_of_experience = $_POST['years_of_experience'];
        $education_level = $_POST['education_level'];

        // Handle photo upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $photo_path = 'uploads/' . time() . '_' . $_FILES['photo']['name'];
            move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path);

            // Update tutor profile with new photo path
            $update_sql = "UPDATE tutors SET first_name = ?, last_name = ?, bio = ?, phone_number = ?, location = ?, subject_specialization = ?, years_of_experience = ?, education_level = ?, photo_path = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ssssssiiis", $first_name, $last_name, $bio, $phone_number, $location, $subject_specialization, $years_of_experience, $education_level, $photo_path, $user_id);
            $update_stmt->execute();
        } else {
            // Update tutor profile without photo
            $update_sql = "UPDATE tutors SET first_name = ?, last_name = ?, bio = ?, phone_number = ?, location = ?, subject_specialization = ?, years_of_experience = ?, education_level = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ssssssiiis", $first_name, $last_name, $bio, $phone_number, $location, $subject_specialization, $years_of_experience, $education_level, $user_id);
            $update_stmt->execute();
        }

        $_SESSION['message'] = "Profile updated successfully!";
    }

    header('Location: profile_settings.php'); // Redirect after form submission
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings</title>
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

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">
            <img src="logo.png" alt="Study Pool">
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="profile_settings.php">Profile Settings</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="signout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="form-container">
        <h1>Update Your Profile</h1>
        <?php if (isset($_SESSION['message'])) { ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['message']; ?>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php } ?>

        <form action="profile_settings.php" method="POST" enctype="multipart/form-data">
            <?php if ($role == 'student') { ?>
                <!-- Student Profile Form -->
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" value="<?php echo isset($user['username']) ? $user['username'] : ''; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo isset($user['email']) ? $user['email'] : ''; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="verification_status" class="form-label">Verification Status</label>
                    <select name="verification_status" id="verification_status" class="form-control">
                        <option value="pending" <?php echo (isset($user['verification_status']) && $user['verification_status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="approved" <?php echo (isset($user['verification_status']) && $user['verification_status'] == 'approved') ? 'selected' : ''; ?>>Approved</option>
                        <option value="rejected" <?php echo (isset($user['verification_status']) && $user['verification_status'] == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                    </select>
                </div>
            <?php } elseif ($role == 'tutor') { ?>
                <!-- Tutor Profile Form -->
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo isset($user['first_name']) ? $user['first_name'] : ''; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo isset($user['last_name']) ? $user['last_name'] : ''; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="photo" class="form-label">Profile Photo</label>
                    <input type="file" name="photo" id="photo" class="form-control">
                    <p>Current Photo: <?php echo isset($user['photo_path']) ? $user['photo_path'] : 'None'; ?></p>
                </div>
                <div class="mb-3">
                    <label for="bio" class="form-label">Bio</label>
                    <textarea name="bio" id="bio" class="form-control"><?php echo isset($user['bio']) ? $user['bio'] : ''; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-control" value="<?php echo isset($user['phone_number']) ? $user['phone_number'] : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" name="location" id="location" class="form-control" value="<?php echo isset($user['location']) ? $user['location'] : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="subject_specialization" class="form-label">Subject Specialization</label>
                    <input type="text" name="subject_specialization" id="subject_specialization" class="form-control" value="<?php echo isset($user['subject_specialization']) ? $user['subject_specialization'] : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="years_of_experience" class="form-label">Years of Experience</label>
                    <input type="number" name="years_of_experience" id="years_of_experience" class="form-control" value="<?php echo isset($user['years_of_experience']) ? $user['years_of_experience'] : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="education_level" class="form-label">Education Level</label>
                    <input type="text" name="education_level" id="education_level" class="form-control" value="<?php echo isset($user['education_level']) ? $user['education_level'] : ''; ?>">
                </div>
            <?php } ?>

            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>

<footer class="footer">
    <p>&copy; 2024 Study Pool. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
