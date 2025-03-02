<?php
include '../includes/config.php';
include '../includes/header.php';
?>

<!-- About Section -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h2 class="display-4 text-primary mb-4">About Us</h2>
            <p class="lead text-muted mb-4">
                Welcome to the Tour Travel Management System. We are dedicated to providing you with the best travel experiences to your favorite destinations. 
                Our team works tirelessly to curate exclusive travel packages that suit every traveler’s need.
            </p>
            <p class="mb-5">
                Our mission is to make travel easy and affordable for everyone, providing excellent customer service and ensuring your journey is nothing short of amazing.
            </p>
            <!-- Call to Action Button -->
            <a href="contact.php" class="btn btn-primary btn-lg px-4 py-2 rounded-3 shadow-lg">
                Get in Touch with Us
            </a>
        </div>
    </div>
</div>

<!-- Background Image Section for Visual Appeal -->
<div class="about-background text-center text-white py-5" style="background-image: url('images/tour-about-background.jpg'); background-size: cover; background-position: center;">
    <div class="container">
        <h3 class="display-4 mb-4">Your Dream Vacation Starts Here</h3>
        <p class="lead mb-4">Explore our exclusive tours to stunning destinations all over the world. Let us help you plan the perfect getaway!</p>
        <a href="tours.php" class="btn btn-light btn-lg px-5 py-3 rounded-3 shadow-lg">Explore Our Tours</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
