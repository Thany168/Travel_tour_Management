<?php
// Include database connection and header
include '../includes/config.php';
include '../includes/header.php';

// Initialize messages
$error_msg = "";
$success_msg = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $package_id = $_POST['package_id'];
    $user_email = $_POST['user_email'];
    $from_date = $_POST['from_date'];  
    $to_date = $_POST['to_date'];    
    $comment = $_POST['comment'];

    // Validate the form fields
    if (empty($package_id) || empty($user_email) || empty($from_date) || empty($to_date)) {
        $error_msg = "Please fill in all required fields.";
    } else {
        // Check if the package has already been booked by this user
        $checkBookingQuery = "SELECT * FROM tb_booking WHERE package_id = ? AND user_email = ?";
        $stmt = $conn->prepare($checkBookingQuery);
        $stmt->bind_param("is", $package_id, $user_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // If there's an existing booking with the same package and user email
            $error_msg = "You have already booked this package. Please check your existing bookings.";
        } else {
            try {
                // Insert the new booking into the database
                $sql = "INSERT INTO tb_booking (package_id, user_email, fromDate, toDate, comment, register_date, status) 
                        VALUES (?, ?, ?, ?, ?, NOW(), 'Pending')";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("issss", $package_id, $user_email, $from_date, $to_date, $comment);
                $stmt->execute();

                $success_msg = "Booking successful! We will confirm your booking soon.";
            } catch (mysqli_sql_exception $e) {
                $error_msg = "There was an error with your booking. Please try again.";
            }
        }
    }
}

// Fetch available packages
$result = $conn->query("SELECT * FROM tb_tourpackages ORDER BY package_id DESC");

?>

<div class="container">
    <h2 class="text-center my-4">Book Your Tour Package</h2>

    <?php if ($error_msg) { echo '<div class="alert alert-danger">' . $error_msg . '</div>'; } ?>
    <?php if ($success_msg) { echo '<div class="alert alert-success">' . $success_msg . '</div>'; } ?>

    <form method="POST" action="booking.php">
        <div class="mb-3">
            <label for="package_id" class="form-label">Select Package</label>
            <select class="form-control" id="package_id" name="package_id" required>
                <option value="">Select a package</option>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <option value="<?php echo $row['package_id']; ?>"><?php echo $row['package_name']; ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="user_email" class="form-label">Your Email</label>
            <input type="email" class="form-control" id="user_email" name="user_email" required>
        </div>

        <div class="mb-3">
            <label for="from_date" class="form-label">From Date</label>
            <input type="date" class="form-control" id="from_date" name="from_date" required>
        </div>

        <div class="mb-3">
            <label for="to_date" class="form-label">To Date</label>
            <input type="date" class="form-control" id="to_date" name="to_date" required>
        </div>

        <div class="mb-3">
            <label for="comment" class="form-label">Additional Comments</label>
            <textarea class="form-control" id="comment" name="comment"></textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100">Book Now</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
