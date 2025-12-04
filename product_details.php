<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

if (!isset($_GET['id'])) {
    redirect('products.php');
}

$product_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    redirect('products.php');
}

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $quantity = (int) $_POST['quantity'];

    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add or update item in cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    $success_msg = "Product added to cart!";
}

include 'includes/header.php';
?>

<div class="row">
    <!-- Product Image -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0">
            <img src="assets/images/<?php echo htmlspecialchars($product['image'] ?? 'placeholder.jpg'); ?>"
                class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
    </div>

    <!-- Product Info -->
    <div class="col-md-6">
        <h1 class="fw-bold text-primary"><?php echo htmlspecialchars($product['name']); ?></h1>
        <p class="text-muted">Category: <a
                href="products.php?category=<?php echo $product['category_id']; ?>"><?php echo htmlspecialchars($product['category_name']); ?></a>
        </p>

        <div class="mb-3">
            <?php if ($product['discount'] > 0): ?>
                <h3 class="text-muted text-decoration-line-through d-inline me-2">
                    <?php echo formatPrice($product['price']); ?></h3>
                <h2 class="text-danger fw-bold d-inline"><?php echo formatPrice($product['final_price']); ?></h2>
                <span class="badge bg-danger ms-2">Save <?php echo formatPrice($product['discount']); ?></span>
            <?php else: ?>
                <h2 class="text-primary fw-bold"><?php echo formatPrice($product['price']); ?></h2>
            <?php endif; ?>
        </div>

        <p class="lead"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

        <hr>

        <?php if (isset($success_msg)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success_msg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="d-flex align-items-center mb-4">
            <div class="me-3">
                <label class="form-label visually-hidden">Quantity</label>
                <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>"
                    class="form-control" style="width: 80px;">
            </div>
            <button type="submit" name="add_to_cart" class="btn btn-primary btn-lg flex-grow-1">
                <i class="fas fa-cart-plus me-2"></i> Add to Jadaa Mart Cart
            </button>
        </form>

        <div class="card bg-light border-0">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-truck me-2"></i>Delivery Info</h5>
                <p class="card-text mb-0">Fast delivery within 2-3 days.</p>
                <p class="card-text">Free shipping on orders over $50.</p>
            </div>
        </div>
    </div>
</div>

<!-- Similar Products (Simple implementation: same category) -->
<div class="mt-5">
    <h3 class="fw-bold mb-4">Similar Products</h3>
    <?php
    $sim_stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? AND id != ? LIMIT 4");
    $sim_stmt->execute([$product['category_id'], $product_id]);
    $similar_products = $sim_stmt->fetchAll();
    ?>
    <div class="row">
        <?php foreach ($similar_products as $sim_prod): ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <img src="assets/images/<?php echo htmlspecialchars($sim_prod['image'] ?? 'placeholder.jpg'); ?>"
                        class="card-img-top" alt="<?php echo htmlspecialchars($sim_prod['name']); ?>"
                        style="height: 150px; object-fit: cover;">
                    <div class="card-body">
                        <h6 class="card-title text-truncate"><?php echo htmlspecialchars($sim_prod['name']); ?></h6>
                        <p class="card-text fw-bold text-primary"><?php echo formatPrice($sim_prod['final_price']); ?></p>
                        <a href="product_details.php?id=<?php echo $sim_prod['id']; ?>"
                            class="btn btn-sm btn-outline-primary w-100">View</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>