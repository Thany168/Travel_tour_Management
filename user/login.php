<?php
// Include the database connection file
include '../includes/config.php';

// Initialize variables
$useremail = "";
$userpassword = "";
$error_msg = "";
$success_msg = "";

// Start session for login check
session_start();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the posted email and password and sanitize them
    $useremail = filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL);
    $userpassword = $_POST['user_password'];

    // Validate fields
    if (empty($useremail) || empty($userpassword)) {
        $error_msg = "Please fill in both fields.";
    } else {
        // Check if email exists in the database
        $sql = "SELECT * FROM tb_users WHERE user_email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $useremail);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($userpassword, $user['user_password'])) {
                // Set session for the logged-in user
                $_SESSION['user_id'] = $user['user_id']; // assuming 'user_id' is the unique user identifier
                $_SESSION['user_email'] = $user['user_email']; // store the user email in session as well
                
                // Redirect to the homepage after successful login
                header("Location: ../index.php");
                exit();
            } else {
                $error_msg = "Invalid password.";
            }
        } else {
            $error_msg = "No account found with this email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tour Travel Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center bg-primary text-white">
                        <h3>Login</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($error_msg) { echo '<div class="alert alert-danger">' . $error_msg . '</div>'; } ?>
                        <?php if ($success_msg) { echo '<div class="alert alert-success">' . $success_msg . '</div>'; } ?>
                        <form method="POST" action="login.php">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="user_email" value="<?php echo htmlspecialchars($useremail); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="user_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p>Don't have an account? <a href="register.php">Register</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
