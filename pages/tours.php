<?php
include '../includes/config.php';
include '../includes/header.php';
?>

<!-- Hero Section -->
<div class="jumbotron text-center bg-primary text-white" style="background-image: url('images/tours-hero.jpg'); background-size: cover; background-position: center;">
    <h1 class="display-4 text-shadow">Explore Our Tour Packages</h1>
    <p class="lead text-shadow">Find your perfect destination and start your adventure today.</p>
</div>

<!-- Search and Filter Section -->
<div class="container my-4">
    <form method="GET" action="tours.php" class="row g-3">
        <div class="col-md-6">
            <input type="text" name="search" class="form-control" placeholder="Search tours..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        </div>
        <div class="col-md-4">
            <select name="sort" class="form-select">
                <option value="price_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : ''; ?>>Price: Low to High</option>
                <option value="price_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : ''; ?>>Price: High to Low</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>
</div>

<!-- Tours Listing Section -->
<div class="container">
    <div class="row">
    <?php
    // Limit the number of packages displayed initially (e.g., show the first 3 or 4)
    $limit = 10;  // Change this to 3 or 4 as per your requirement
    $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
    
    // Query to fetch tour packages with search and sort functionality
    $query = "SELECT * FROM tb_tourpackages WHERE package_name LIKE '%$search%'";
    
    if ($sort == 'price_asc') {
        $query .= " ORDER BY package_price ASC";
    } elseif ($sort == 'price_desc') {
        $query .= " ORDER BY package_price DESC";
    }
    
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $count = 0;  // Counter to limit the number of displayed items
        while ($row = $result->fetch_assoc()) {
            if ($count < $limit) {
                $imagePath = $row['package_image'];
                $imgSrc = (filter_var($imagePath, FILTER_VALIDATE_URL) || file_exists($imagePath)) ? $imagePath : 'images/placeholder.jpg';
    ?>
    <div class='col-md-4 mb-4 package-item'>
        <div class='card border-0 shadow-lg'>
            <img src='<?php echo $imgSrc; ?>' class='card-img-top rounded' alt='<?php echo htmlspecialchars($row['package_name'], ENT_QUOTES, 'UTF-8'); ?>' style='height: 250px; object-fit: cover;'>
            <div class='card-body'>
                <h5 class='card-title fw-bold text-primary'><?php echo htmlspecialchars($row['package_name'], ENT_QUOTES, 'UTF-8'); ?></h5>
                <p class='card-text text-muted'><?php echo htmlspecialchars($row['package_feature'], ENT_QUOTES, 'UTF-8'); ?></p>
                <div class='d-flex align-items-center'>
                    <span class='badge bg-primary me-2'>4.6/5</span>
                    <span class='text-secondary'>134 reviews</span>
                </div>
                <p class='mt-2'><strong>From $<?php echo number_format($row['package_price'], 2); ?></strong></p>
                <a href='../Travel_tour_Management/pages/tour-details.php?id=<?php echo $row['package_id']; ?>' class='btn btn-primary w-100'>View Details</a>
            </div>
        </div>
    </div>
    <?php
                $count++;
            }
        }
    } else {
        echo "<div class='col-12 text-center'><p>No tours found.</p></div>";
    }
    ?>
</div>
</div>

<?php include '../includes/footer.php'; ?>
