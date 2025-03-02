<?php
// Include config and header files
include '../includes/config.php';
include '../includes/header.php';

// Start the session
session_start();

// Destroy the session to log out the user
session_unset();
session_destroy();

// Redirect to index.php after logout
header("Location: ../index.php");
exit();
?>
