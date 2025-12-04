<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
include 'includes/header.php';

// Handle Add Product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = sanitize($_POST['name']);
    $slug = strtolower(str_replace(' ', '-', $name)) . '-' . rand(100, 999);
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $description = sanitize($_POST['description']);
    $stock = $_POST['stock'];

    // Handle Discount
    $discount = 0;
    if (isset($_POST['has_discount'])) {
        $discount = $_POST['discount_amount'];
    }

    // Simple Image Upload (In real app, add validation)
    $image = 'placeholder.jpg'; // Default
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/images/";
        $image_name = time() . '_' . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image_name);
        $image = $image_name;
    }

    $stmt = $pdo->prepare("INSERT INTO products (name, slug, description, price, discount, category_id, stock, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $slug, $description, $price, $discount, $category_id, $stock, $image]);
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
    echo "<script>window.location.href='products.php';</script>";
}

// Fetch Products
$products = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Products</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
        <i class="fas fa-plus"></i> Add Product
    </button>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><img src="../assets/images/<?php echo $product['image']; ?>" width="50" height="50"
                                style="object-fit: cover;"></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                        <td>$<?php echo number_format($product['price'], 2); ?></td>
                        <td>
                            <?php if ($product['discount'] > 0): ?>
                                <span class="badge bg-danger">-$<?php echo number_format($product['discount'], 2); ?></span>
                            <?php else: ?>
                                <span class="badge bg-secondary">None</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $product['stock']; ?></td>
                        <td>
                            <a href="?delete=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" required>
                    </div>

                    <!-- Discount Section -->
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="hasDiscount" name="has_discount">
                            <label class="form-check-label" for="hasDiscount">Has Discount?</label>
                        </div>
                    </div>
                    <div class="mb-3 d-none" id="discountField">
                        <label class="form-label">Discount Amount</label>
                        <input type="number" step="0.01" name="discount_amount" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stock</label>
                        <input type="number" name="stock" class="form-control" value="10">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_product" class="btn btn-primary">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('hasDiscount').addEventListener('change', function () {
        const discountField = document.getElementById('discountField');
        if (this.checked) {
            discountField.classList.remove('d-none');
        } else {
            discountField.classList.add('d-none');
        }
    });
</script>

<?php include 'includes/footer.php'; ?>