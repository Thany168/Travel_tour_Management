<?php

// Include the database connection file
include('../includes/config.php');

// Initialize variables
$username = "";
$userphoneNumber = "";
$useremail = "";
$userpassword = "";
$error_msg = "";
$success_msg = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user inputs
    $username = filter_var(trim($_POST['user_name']), FILTER_SANITIZE_STRING);
    $userphoneNumber = filter_var(trim($_POST['user_phoneNumber']), FILTER_SANITIZE_STRING);
    $useremail = filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL);
    $userpassword = $_POST['user_password'];
    $profile_picture = $_FILES['profile_picture'];

    // Validate fields
    if (empty($username) || empty($userphoneNumber) || empty($useremail) || empty($userpassword) || empty($profile_picture['name'])) {
        $error_msg = "Please fill in all fields.";
    } elseif (!filter_var($useremail, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Invalid email format.";
    } elseif (strlen($userpassword) < 8) {
        $error_msg = "Password must be at least 8 characters long.";
    } else {
        // Check if email already exists
        $sql = "SELECT * FROM tb_users WHERE user_email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $useremail);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_msg = "An account with this email already exists.";
        } else {
            // Upload the profile picture
            $upload_dir = 'images/';

            // Create the directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Generate a unique filename to prevent overwriting
            $file_extension = pathinfo($profile_picture['name'], PATHINFO_EXTENSION);
            $unique_filename = time() . "_" . uniqid() . "." . $file_extension;
            $target_file = $upload_dir . $unique_filename;

            // Check if file is a valid image
            if (getimagesize($profile_picture['tmp_name'])) {
                if (move_uploaded_file($profile_picture['tmp_name'], $target_file)) {
                    // Hash the password before storing
                    $hashed_password = password_hash($userpassword, PASSWORD_DEFAULT);

                    // Insert new user into the database
                    $sql = "INSERT INTO tb_users (user_name, user_phoneNumber, user_email, user_password, profile_picture) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssss", $username, $userphoneNumber, $useremail, $hashed_password, $target_file);

                    if ($stmt->execute()) {
                        // Start session and store user info
                        session_start();
                        $_SESSION['user_id'] = $stmt->insert_id; // Store user ID from the newly inserted record
                        $_SESSION['user_email'] = $useremail;

                        // Redirect after successful registration
                        header("Location: login.php");
                        exit();
                    } else {
                        $error_msg = "Error occurred while registering. Please try again.";
                    }
                } else {
                    $error_msg = "Error uploading profile picture. Please try again.";
                }
            } else {
                $error_msg = "Please upload a valid image file.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Tour Travel Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center bg-primary text-white">
                        <h3>Register</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($error_msg) { echo '<div class="alert alert-danger">' . $error_msg . '</div>'; } ?>
                        <?php if ($success_msg) { echo '<div class="alert alert-success">' . $success_msg . '</div>'; } ?>
                        <form method="POST" action="register.php" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullname" name="user_name" value="<?php echo htmlspecialchars($username); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="user_phoneNumber" value="<?php echo htmlspecialchars($userphoneNumber); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="user_email" value="<?php echo htmlspecialchars($useremail); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="user_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="profile_picture" class="form-label">Profile Picture</label>
                                <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept=".jpg, .jpeg, .png" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p>Already have an account? <a href="/user/login.php">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
