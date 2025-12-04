<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// Build Query
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

// Filter by Category
if (isset($_GET['category']) && is_numeric($_GET['category'])) {
    $sql .= " AND category_id = ?";
    $params[] = $_GET['category'];
}

// Search
if (isset($_GET['search'])) {
    $sql .= " AND name LIKE ?";
    $params[] = "%" . $_GET['search'] . "%";
}

// Sorting
$sort = $_GET['sort'] ?? 'newest';
switch ($sort) {
    case 'price_asc':
        $sql .= " ORDER BY final_price ASC";
        break;
    case 'price_desc':
        $sql .= " ORDER BY final_price DESC";
        break;
    default:
        $sql .= " ORDER BY created_at DESC";
        break;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Fetch Categories for Sidebar
$cat_stmt = $pdo->query("SELECT * FROM categories");
$categories = $cat_stmt->fetchAll();
?>

<div class="row">
    <!-- Sidebar Filters -->
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">Filters</div>
            <div class="card-body">
                <form action="" method="GET">
                    <div class="mb-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control"
                            value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
                            placeholder="Product name...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort By</label>
                        <select name="sort" class="form-select">
                            <option value="newest" <?php echo ($sort == 'newest') ? 'selected' : ''; ?>>Newest</option>
                            <option value="price_asc" <?php echo ($sort == 'price_asc') ? 'selected' : ''; ?>>Price: Low
                                to High</option>
                            <option value="price_desc" <?php echo ($sort == 'price_desc') ? 'selected' : ''; ?>>Price:
                                High to Low</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="col-md-9">
        <h2 class="mb-4 text-primary fw-bold">All Products</h2>
        <?php if (count($products) > 0): ?>
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4 mb-4">
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
        <?php else: ?>
            <div class="alert alert-info">No products found matching your criteria.</div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>