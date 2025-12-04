<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// Fetch Featured Products (Newest 4)
$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 4");
$featured_products = $stmt->fetchAll();

// Fetch Categories
$stmt = $pdo->query("SELECT * FROM categories LIMIT 3");
$categories = $stmt->fetchAll();
?>

<!-- Hero Slider -->
<div id="heroCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
    <div class="carousel-inner rounded shadow-sm">
        <div class="carousel-item active">
            <div class="p-5 text-white bg-primary" style="background: linear-gradient(45deg, #0066CC, #0099FF);">
                <div class="container py-5">
                    <h1 class="display-4 fw-bold">Welcome to Jadaa Mart</h1>
                    <p class="lead">The best offers right at your fingertips.</p>
                    <a href="products.php" class="btn btn-light btn-lg text-primary fw-bold">Shop Now</a>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="p-5 text-white bg-success" style="background: linear-gradient(45deg, #28a745, #20c997);">
                <div class="container py-5">
                    <h1 class="display-4 fw-bold">New Arrivals</h1>
                    <p class="lead">Check out the latest gadgets and fashion.</p>
                    <a href="products.php" class="btn btn-light btn-lg text-success fw-bold">View Products</a>
                </div>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- Categories Section -->
<section class="mb-5">
    <h2 class="text-center mb-4 fw-bold text-primary">Shop by Category</h2>
    <div class="row">
        <?php foreach ($categories as $category): ?>
            <div class="col-md-4 mb-3">
                <div class="card h-100 shadow-sm border-0 hover-card">
                    <div class="card-body text-center d-flex flex-column justify-content-center">
                        <h3 class="card-title"><?php echo htmlspecialchars($category['name']); ?></h3>
                        <p class="card-text text-muted"><?php echo htmlspecialchars($category['description']); ?></p>
                        <a href="products.php?category=<?php echo $category['id']; ?>"
                            class="btn btn-outline-primary mt-auto">Browse</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Featured Products Section -->
<section class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">Featured Products</h2>
        <a href="products.php" class="btn btn-outline-primary">View All</a>
    </div>
    <div class="row">
        <?php foreach ($featured_products as $product): ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm border-0 product-card">
                    <div class="position-relative">
                        <?php if ($product['discount'] > 0): ?>
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</span>
                        <?php endif; ?>
                        <img src="assets/images/<?php echo htmlspecialchars($product['image'] ?? 'placeholder.jpg'); ?>"
                            class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>"
                            style="height: 200px; object-fit: cover;">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-truncate"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <div class="mb-2">
                            <?php if ($product['discount'] > 0): ?>
                                <span
                                    class="text-muted text-decoration-line-through me-2"><?php echo formatPrice($product['price']); ?></span>
                                <span class="text-danger fw-bold"><?php echo formatPrice($product['final_price']); ?></span>
                            <?php else: ?>
                                <span class="fw-bold text-primary"><?php echo formatPrice($product['price']); ?></span>
                            <?php endif; ?>
                        </div>
                        <a href="product_details.php?id=<?php echo $product['id']; ?>"
                            class="btn btn-primary mt-auto w-100">View Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Special Offer Banner -->
<section class="mb-5">
    <div class="p-5 rounded text-white text-center" style="background: linear-gradient(135deg, #6610f2, #fd7e14);">
        <h2 class="display-5 fw-bold">Special Offer Today!</h2>
        <p class="lead">Get 20% off on all Electronics. Use code: JADAA20</p>
        <a href="products.php?category=1" class="btn btn-light btn-lg fw-bold">Shop Electronics</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>