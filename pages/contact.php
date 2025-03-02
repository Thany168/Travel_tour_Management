<?php
include '../includes/config.php';
include '../includes/header.php';
?>

<!-- Contact Section -->
<div class="container my-5">
    <h2 class="text-center my-4">Contact Us</h2>
    <div class="row">
        <div class="col-md-6">
            <h4>Get in touch with us</h4>
            <p>If you have any questions or need assistance, feel free to reach out to us. Our team is here to help!</p>
            <form action="submit_contact_form.php" method="post">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Your Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="message">Your Message</label>
                    <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <div class="col-md-6">
            <h4>Our Location</h4>
            <p>123 Travel Road, City, Country</p>
            <p>Phone: (123) 456-7890</p>
            <p>Email: info@tourtravel.com</p>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
