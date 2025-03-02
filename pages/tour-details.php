<?php
// Include the database connection file
include '../includes/config.php';
include '../includes/header.php';

// Get the tour ID from the URL
if (isset($_GET['id'])) {
    $tour_id = $_GET['id'];

    // Fetch the tour details from the database
    $sql = "SELECT * FROM tb_tourpackages WHERE package_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tour_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the data
        $tour = $result->fetch_assoc();
    } else {
        echo "<p>Tour not found.</p>";
        exit();
    }
} else {
    echo "<p>No tour selected.</p>";
    exit();
}
?>

<div class="container">
    <div class="row mt-5">
        <div class="col-md-6">
            <img src="<?php echo $tour['package_image']; ?>" class="img-fluid" alt="<?php echo $tour['package_name']; ?>">
        </div>
        <div class="col-md-6">
            <h2><?php echo $tour['package_name']; ?></h2>
            <h4><?php echo $tour['package_type']; ?></h4>
            <p><strong>Location: </strong><?php echo $tour['package_location']; ?></p>
            <p><strong>Price: </strong>$<?php echo number_format($tour['package_price'], 2); ?></p>
            <h5>Features</h5>
            <p><?php echo $tour['package_feature']; ?></p>
            <h5>Details</h5>
            <p><?php echo $tour['package_detail']; ?></p>
            <a href="booking.php?tour_id=<?php echo $tour['package_id']; ?>" class="btn btn-primary">Book Now</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

