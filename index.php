<?php
include 'includes/config.php';
include 'includes/header.php';
?>

<!-- Hero Section with Animated Slider -->
<div id="heroCarousel" class="carousel slide carousel-fade" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="jumbotron text-center bg-primary text-white" style="background-image: url('images/hero-bg1.jpg'); background-size: cover; background-position: center;">
                <h1 class="display-4 text-shadow">Welcome to the Tour Travel Management System</h1>
                <p class="lead text-shadow">Explore amazing destinations with our exclusive travel packages.</p>
                <a href="tours.php" class="btn btn-light btn-lg">Browse Tours</a>
            </div>
        </div>
        <div class="carousel-item">
            <div class="jumbotron text-center bg-primary text-white" style="background-image: url('images/hero-bg2.jpg'); background-size: cover; background-position: center;">
                <h1 class="display-4 text-shadow">Your Next Adventure Awaits</h1>
                <p class="lead text-shadow">Book a tour today and start your journey to the world’s most exciting destinations.</p>
                <a href="tours.php" class="btn btn-light btn-lg">Browse Tours</a>
            </div>
        </div>
        <div class="carousel-item">
            <div class="jumbotron text-center bg-primary text-white" style="background-image: url('images/hero-bg3.jpg'); background-size: cover; background-position: center;">
                <h1 class="display-4 text-shadow">Discover Beautiful Places</h1>
                <p class="lead text-shadow">Join us on unforgettable trips to breathtaking destinations across the globe.</p>
                <a href="tours.php" class="btn btn-light btn-lg">Browse Tours</a>
            </div>
        </div>
    </div>

    <!-- Carousel Controls -->
    <a class="carousel-control-prev" href="#heroCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#heroCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>


<div class="container my-5">
    <h2 class="fw-bold mb-4 text-center">Featured Properties</h2>
  
    <!-- Button Group for Location Filter -->
    <div class="d-flex justify-content-center mb-4">
        <div class="btn-group" role="group" id="locationButtonsContainer">
            <?php
            // Fetch distinct locations from the database
            $locationQuery = "SELECT DISTINCT package_location FROM tb_tourpackages";
            $locationResult = $conn->query($locationQuery);

            $locations = [];
            if ($locationResult->num_rows > 0) {
                // Store locations in an array
                while ($locationRow = $locationResult->fetch_assoc()) {
                    $locations[] = htmlspecialchars($locationRow['package_location'], ENT_QUOTES, 'UTF-8');
                }
            }

            // Limit the number of locations displayed initially (e.g., show first 3 locations)
            $displayLimit = 3;
            $totalLocations = count($locations);
            $visibleLocations = array_slice($locations, 0, $displayLimit);
            
            // Loop through and display a button for each visible location
            foreach ($visibleLocations as $location) {
                echo "<button class='btn btn-light location-button px-3' onclick='filterByLocation(\"$location\")'>$location</button>";
            }

            // Display "See More" button if there are more locations
            if ($totalLocations > $displayLimit) {
                echo "<button class='btn btn-link' onclick='toggleLocationVisibility()' id='seeMoreBtn'>See More</button>";
            }
            ?>
        </div>
    </div>

<script>
    // Function to toggle visibility of additional locations
    function toggleLocationVisibility() {
        var allButtons = document.querySelectorAll('.location-button');
        var seeMoreBtn = document.getElementById('seeMoreBtn');
        var displayLimit = 3;  // Set the number of locations to display initially

        // Toggle the visibility of buttons
        if (seeMoreBtn.innerHTML === "See More") {
            // Show all buttons
            allButtons.forEach(function (btn, index) {
                if (index >= displayLimit) {
                    btn.style.display = 'inline-block';  // Show more buttons
                }
            });
            seeMoreBtn.innerHTML = "See Less";  // Change button text to "See Less"
        } else {
            // Hide extra buttons
            allButtons.forEach(function (btn, index) {
                if (index >= displayLimit) {
                    btn.style.display = 'none';  // Hide extra buttons
                }
            });
            seeMoreBtn.innerHTML = "See More";  // Change button text back to "See More"
        }
    }
</script>


    <!-- Script to handle location filter -->
    <script>
        function filterByLocation(location) {
            // Redirect to the same page with the location as a GET parameter
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('location', location);
            window.location.search = urlParams.toString();
        }
    </script>

<div class="row">
    <?php
    $locationFilter = isset($_GET['location']) ? $conn->real_escape_string($_GET['location']) : '';
    
    $limit = 3;
    $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
    
    // Query to fetch tour packages with location filter, search, and sort functionality
    $query = "SELECT * FROM tb_tourpackages WHERE package_name LIKE '%$search%'";
    
    if ($locationFilter) {
        // Filter packages by location if a location is selected
        $query .= " AND package_location = '$locationFilter'";
    }

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
    <div class="col-md-4 col-sm-6 col-12 mb-4 package-item">
        <div class="card border-0 shadow-lg h-100">
            <img src="<?php echo $imgSrc; ?>" class="card-img-top rounded" alt="<?php echo htmlspecialchars($row['package_name'], ENT_QUOTES, 'UTF-8'); ?>" style="height: 200px; object-fit: cover;"/>
            <div class="card-body">
                <h5 class="card-title fw-bold text-primary"><?php echo htmlspecialchars($row['package_name'], ENT_QUOTES, 'UTF-8'); ?></h5>
                <p class="card-text text-muted"><?php echo htmlspecialchars($row['package_feature'], ENT_QUOTES, 'UTF-8'); ?></p>
                <div class="d-flex align-items-center">
                    <span class="badge bg-primary me-2">4.6/5</span>
                    <span class="text-secondary">134 reviews</span>
                </div>
                <p class="mt-2"><strong>From $<?php echo number_format($row['package_price'], 2); ?></strong></p>
                <a href="../Travel_tour_Management/pages/tour-details.php?id=<?php echo $row['package_id']; ?>" class="btn btn-primary w-100">View Details</a>
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


    <!-- See More Button -->
    <?php
    if ($result->num_rows > $limit) {
        echo "<div class='text-center'><button class='btn btn-link' id='seeMoreBtn' onclick='toggleItems()'>See More</button></div>";
    }
    ?>

<script>
// JavaScript to toggle visibility of additional items
function toggleItems() {
    const extraItems = document.querySelectorAll('.package-item:nth-child(n+' + (<?php echo $limit + 1; ?>) + ')');
    extraItems.forEach(item => {
        item.style.display = item.style.display === 'none' ? 'block' : 'none';
    });

    // Toggle button text to "See Less"
    const seeMoreBtn = document.getElementById('seeMoreBtn');
    if (seeMoreBtn.textContent === 'See More') {
        seeMoreBtn.textContent = 'See Less';
    } else {
        seeMoreBtn.textContent = 'See More';
    }
}
</script>


</div>


<!-- Why Choose Us Section -->
<section class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-4">Why Choose Us?</h2>
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <i class="fas fa-globe-americas fa-4x text-primary"></i>
                <h4 class="mt-3">Global Destinations</h4>
                <p>Discover exotic destinations worldwide, tailored to your preferences and interests.</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <i class="fas fa-users fa-4x text-primary"></i>
                <h4 class="mt-3">Customer Support</h4>
                <p>Our dedicated team is available 24/7 to assist you with booking and any inquiries.</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <i class="fas fa-dollar-sign fa-4x text-primary"></i>
                <h4 class="mt-3">Best Price Guarantee</h4>
                <p>We ensure you get the best value for your money, with no hidden fees.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5 bg-secondary text-white">
    <div class="container">
        <h2 class="text-center mb-4">What Our Clients Say</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white border-0">
                    <div class="card-body">
                        <p class="card-text">"An unforgettable experience! The tour was amazing, and the service was exceptional. Highly recommend!"</p>
                        <footer class="blockquote-footer text-white">John Doe, USA</footer>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white border-0">
                    <div class="card-body">
                        <p class="card-text">"Everything was perfectly organized. We had so much fun and are already planning our next trip!"</p>
                        <footer class="blockquote-footer text-white">Jane Smith, UK</footer>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white border-0">
                    <div class="card-body">
                        <p class="card-text">"Excellent service and beautiful locations! The entire process was seamless and stress-free."</p>
                        <footer class="blockquote-footer text-white">Carlos Garcia, Spain</footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<div class="text-center py-5 bg-primary text-white">
    <h2>Ready to explore the world?</h2>
    <p class="lead">Browse through our exclusive travel packages and start your adventure today!</p>
    <a href="tours.php" class="btn btn-light btn-lg">Browse Tours</a>
</div>

<?php include './includes/footer.php'; ?> 
