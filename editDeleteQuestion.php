<?php
include 'dbConnection.php';
session_start();

// Check if the user is logged in and has a student role
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Get question ID from URL
if (isset($_GET['id'])) {
    $question_id = $_GET['id'];

    // Fetch the question details from the database
    $question_query = "SELECT * FROM questions WHERE id = $question_id AND user_id = $user_id";
    $question_result = $conn->query($question_query);

    if ($question_result->num_rows > 0) {
        $question_data = $question_result->fetch_assoc();
    } else {
        echo "Question not found or you don't have permission to edit it.";
        exit();
    }
} else {
    echo "Invalid question ID.";
    exit();
}

// Handle form submission to save changes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $conn->real_escape_string($_POST['category']);
    $question_text = $conn->real_escape_string($_POST['description']);
    $budget = $_POST['budget'];

    // Update the question details in the database
    $update_query = "UPDATE questions SET category = '$category', question_text = '$question_text', 
                     budget = $budget WHERE id = $question_id AND user_id = $user_id";

    if ($conn->query($update_query) === TRUE) {
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Handle question deletion
if (isset($_POST['delete'])) {
    $delete_query = "DELETE FROM questions WHERE id = $question_id AND user_id = $user_id";
    if ($conn->query($delete_query) === TRUE) {
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Error deleting question: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit/Delete Question</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 0; }
        .container { max-width: 900px; margin: auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { font-weight: bold; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        button { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 5px; }
        button:hover { background-color: #0056b3; }
        .delete-btn { background-color: #dc3545; }
        .delete-btn:hover { background-color: #c82333; }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit or Delete Your Question</h1>

    <?php if (isset($question_data)): ?>
    <form method="POST" action="">
        <div class="form-group">
            <label for="category">Category</label>
            <input type="text" name="category" id="category" value="<?php echo htmlspecialchars($question_data['category']); ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" required><?php echo htmlspecialchars($question_data['question_text']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="budget">Budget</label>
            <input type="number" name="budget" id="budget" value="<?php echo htmlspecialchars($question_data['budget']); ?>" step="0.01" required>
        </div>
        <button type="submit">Save Changes</button>
    </form>

    <form method="POST" action="">
        <button type="submit" name="delete" class="delete-btn">Delete Question</button>
    </form>
    <?php else: ?>
    <p>Invalid question data.</p>
    <?php endif; ?>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</div>

</body>
</html>
