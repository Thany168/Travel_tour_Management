<?php
session_start(); // Start session to check login status
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Travel Management System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .profile-avatar {
            width: 30px;
            height: 30px;
            object-fit: cover;
            border-radius: 50%; /* This ensures the image is circular */
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Tour Travel</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="../../Travel_tour_Management/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../Travel_tour_Management/pages/tours.php">Tours</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../Travel_tour_Management/pages/about.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../Travel_tour_Management/pages/contact.php">Contact</a>
                </li>
                
                <?php
                if (isset($_SESSION['user_id'])) {
                    // User is logged in
                    $user_id = $_SESSION['user_id'];
                    $sql = "SELECT profile_picture FROM tb_users WHERE user_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    // Check if the user has a profile picture, otherwise use default
                    
                    // Account, Profile and Logout links
                    echo "<li class='nav-item'>
                            <a class='nav-link' href='../../Travel_tour_Management/pages/account.php'>Account</a>
                          </li>";
                    echo "<li class='nav-item'>
                            <a class='nav-link' href='../../Travel_tour_Management/user/confirm_logout.php'>Logout</a>
                          </li>";
                } else {
                    // User is not logged in, show login and register links
                    echo "<li class='nav-item'>
                            <a class='nav-link' href='../../Travel_tour_Management/user/login.php'>Login</a>
                          </li>";
                    echo "<li class='nav-item'>
                            <a class='nav-link' href='../../Travel_tour_Management/user/register.php'>Register</a>
                          </li>";
                }
                ?>
            </ul>
        </div>
    </div>
</nav>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
