<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'dbConnection.php';
    $user_id = $_SESSION['user_id'];
    $category = $_POST['category'];  // New category field
    $question_text = $_POST['question_text'];
    $budget = $_POST['budget'];
    $school = $_POST['school'];
    $course = $_POST['course'];
    $delivery_time = $_POST['delivery_time'];

    $query = "INSERT INTO questions (user_id, category, question_text, budget, status, school, course, delivery_time) 
              VALUES ($user_id, '$category', '$question_text', $budget, 'open', '$school', '$course', '$delivery_time')";
    
    if ($conn->query($query) === TRUE) {
        echo "Question posted successfully.";
    } else {
        echo "Error posting question: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Question</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
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
        .quote-section {
            background-color: #f4f4f4;
            padding: 30px;
            text-align: center;
            margin-top: 20px;
        }
        .quote-section h2 {
            font-size: 1.5em;
            color: #333;
        }
        .quote-section p {
            font-size: 1.1em;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
        }
        .category-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 20px;
        }
        .category-card {
            background-color: #fff;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.3s ease-in-out;
        }
        .category-card:hover {
            transform: scale(1.05);
        }
        .category-card h3 {
            font-size: 1.2em;
            margin-bottom: 10px;
            color: #007bff;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            border-radius: 10px;
        }
        .form-container input, .form-container textarea, .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .form-container textarea {
            height: 150px;
            resize: vertical;
        }
        .form-container button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
        }
        .form-container button:hover {
            background-color: #218838;
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

<!-- Quote Section -->
<div class="quote-section">
    <h2>Homework Q&A</h2>
    <p>Get high quality explanations and answers from verified tutors. Tutors can help with all types of questions big or small, ranging 100’s of subjects from basic math to advanced rocket science!</p>
</div>

<!-- Popular Subjects -->
<div class="category-cards">
    <div class="category-card" onclick="showPostForm('Business')">
        <h3>Business</h3>
    </div>
    <div class="category-card" onclick="showPostForm('Economics')">
        <h3>Economics</h3>
    </div>
    <div class="category-card" onclick="showPostForm('Writing')">
        <h3>Writing</h3>
    </div>
    <div class="category-card" onclick="showPostForm('Humanities')">
        <h3>Humanities</h3>
    </div>
    <div class="category-card" onclick="showPostForm('Law')">
        <h3>Law</h3>
    </div>
    <div class="category-card" onclick="showPostForm('Mathematics')">
        <h3>Mathematics</h3>
    </div>
    <div class="category-card" onclick="showPostForm('Science')">
        <h3>Science</h3>
    </div>
    <div class="category-card" onclick="showPostForm('Health & Medical')">
        <h3>Health & Medical</h3>
    </div>
    <div class="category-card" onclick="showPostForm('Computer Science')">
        <h3>Computer Science</h3>
    </div>
    <div class="category-card" onclick="showPostForm('Programming')">
        <h3>Programming</h3>
    </div>
    <div class="category-card" onclick="showPostForm('Engineering')">
        <h3>Engineering</h3>
    </div>
    <div class="category-card" onclick="showPostForm('Foreign Languages')">
        <h3>Foreign Languages</h3>
    </div>
    <div class="category-card" onclick="showPostForm('Other')">
        <h3>Other</h3>
    </div>
</div>

<!-- Form Container -->
<div class="form-container">
    <h2>Describe Your Question</h2>
    <form method="POST" onsubmit="return validateForm()">
        <label for="category">Category:</label>
        <select name="category" id="category" required>
            <option value="Business">Business</option>
            <option value="Economics">Economics</option>
            <option value="Writing">Writing</option>
            <option value="Humanities">Humanities</option>
            <option value="Law">Law</option>
            <option value="Mathematics">Mathematics</option>
            <option value="Science">Science</option>
            <option value="Health & Medical">Health & Medical</option>
            <option value="Computer Science">Computer Science</option>
            <option value="Programming">Programming</option>
            <option value="Engineering">Engineering</option>
            <option value="Foreign Languages">Foreign Languages</option>
            <option value="Other">Other</option>
        </select>

        <label for="question_text">Question:</label>
        <textarea name="question_text" id="question_text" required></textarea>

        <label for="budget">Budget ($):</label>
        <input type="number" name="budget" id="budget" required>

        <label for="school">School:</label>
        <input type="text" name="school" id="school" required>

        <label for="course">Course:</label>
        <input type="text" name="course" id="course" required>

        <label for="delivery_time">Delivery Date:</label>
        <input type="date" name="delivery_time" id="delivery_time" required>

        <button type="submit">Post Question</button>
    </form>
</div>

<!-- Footer -->
<div class="footer">
    <p>&copy; 2024 Study Pool</p>
</div>

<script>
    // Toggle menu visibility on small screens
    function toggleMenu() {
        var menu = document.querySelector('.menu');
        menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
    }

    // Show the question form based on the category
    function showPostForm(category) {
        document.getElementById('question_text').placeholder = 'Describe your question related to ' + category;
    }

    // Form validation
    function validateForm() {
        var questionText = document.getElementById('question_text').value;
        var budget = document.getElementById('budget').value;
        var school = document.getElementById('school').value;
        var course = document.getElementById('course').value;
        var deliveryTime = document.getElementById('delivery_time').value;

        if (!questionText || !budget || !school || !course || !deliveryTime) {
            alert('All fields must be filled out');
            return false;
        }
        return true;
    }
</script>

</body>
</html>
