<?php
session_start();
include('dbconnection.php'); // Include the database connection

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect if user is not logged in
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch notes uploaded by the tutor
$notes_sql = "SELECT id, title, content, created_at, subject, fee FROM notes WHERE user_id = ?";
$notes_stmt = $conn->prepare($notes_sql);
$notes_stmt->bind_param("i", $user_id);
$notes_stmt->execute();
$notes_result = $notes_stmt->get_result();

// Fetch answers uploaded by the tutor
$answers_sql = "SELECT id, question_id, tutor_id, answer_text, attachment_url, created_at FROM answers WHERE tutor_id = ?";
$answers_stmt = $conn->prepare($answers_sql);
$answers_stmt->bind_param("i", $user_id);
$answers_stmt->execute();
$answers_result = $answers_stmt->get_result();

// Fetch book guides uploaded by the tutor
$book_guides_sql = "SELECT id, title, description, price, attachment_url FROM book_guides WHERE tutor_id = ?";
$book_guides_stmt = $conn->prepare($book_guides_sql);
$book_guides_stmt->bind_param("i", $user_id);
$book_guides_stmt->execute();
$book_guides_result = $book_guides_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Uploads</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('https://img.pikbest.com/backgrounds/20200513/creative-synthesis-education-background_2755178.jpg!sw800');
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

<div class="container">
    <h1>Your Uploaded Notes, Answers, and Book Guides</h1>

    <!-- Notes Table -->
    <h2>Notes</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Content</th>
                <th>Subject</th>
                <th>Fee</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($note = $notes_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $note['id']; ?></td>
                    <td><?php echo $note['title']; ?></td>
                    <td><?php echo substr($note['content'], 0, 100); ?>...</td>
                    <td><?php echo $note['subject']; ?></td>
                    <td><?php echo $note['fee']; ?></td>
                    <td><?php echo $note['created_at']; ?></td>
                    <td>
                        <a href="edit_note.php?id=<?php echo $note['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_note.php?id=<?php echo $note['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Answers Table -->
    <h2>Answers</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Question ID</th>
                <th>Answer</th>
                <th>Attachment</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($answer = $answers_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $answer['id']; ?></td>
                    <td><?php echo $answer['question_id']; ?></td>
                    <td><?php echo substr($answer['answer_text'], 0, 100); ?>...</td>
                    <td><a href="<?php echo $answer['attachment_url']; ?>" target="_blank" class="btn btn-info btn-sm">View</a></td>
                    <td><?php echo $answer['created_at']; ?></td>
                    <td>
                        <a href="edit_answer.php?id=<?php echo $answer['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_answer.php?id=<?php echo $answer['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Book Guides Table -->
    <h2>Book Guides</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Price</th>
                <th>Attachment</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($book_guide = $book_guides_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $book_guide['id']; ?></td>
                    <td><?php echo $book_guide['title']; ?></td>
                    <td><?php echo substr($book_guide['description'], 0, 100); ?>...</td>
                    <td><?php echo $book_guide['price']; ?></td>
                    <td><a href="<?php echo $book_guide['attachment_url']; ?>" target="_blank" class="btn btn-info btn-sm">View</a></td>
                    <td>
                        <a href="edit_book_guide.php?id=<?php echo $book_guide['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_book_guide.php?id=<?php echo $book_guide['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Footer -->
<footer class="footer">
    <p>&copy; 2024 StudyPool Clone. All Rights Reserved.</p>
</footer>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>
