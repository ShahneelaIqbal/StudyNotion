if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the uploaded file
    $uploadedFile = $_FILES['user_photo'];

    // Move the uploaded file to a temporary directory
    $filePath = '/path/to/upload/directory/' . basename($uploadedFile['name']);
    move_uploaded_file($uploadedFile['tmp_name'], $filePath);

    // Call the verification function
    $verificationResult = verifyFaceAndID($filePath);

    if ($verificationResult) {
        echo "Verification successful!";
    } else {
        echo "Verification failed.";
    }
}
