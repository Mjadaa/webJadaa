<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['quantities'] as $product_id => $quantity) {
            if ($quantity > 0) {
                $_SESSION['cart'][$product_id] = $quantity;
            } else {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    } elseif (isset($_POST['remove_item'])) {
        $product_id = $_POST['remove_item'];
        unset($_SESSION['cart'][$product_id]);
    }
}

// Fetch Cart Items
$cart_items = [];
$total_price = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
    $products = $stmt->fetchAll();

    foreach ($products as $product) {
        $product['quantity'] = $_SESSION['cart'][$product['id']];
        $product['subtotal'] = $product['final_price'] * $product['quantity'];
        $total_price += $product['subtotal'];
        $cart_items[] = $product;
    }
}

include 'includes/header.php';
?>

<h1 class="mb-4 fw-bold text-primary">Your Shopping Cart</h1>

<?php if (empty($cart_items)): ?>
    <div class="alert alert-info py-5 text-center">
        <h4>Your cart is empty.</h4>
        <a href="products.php" class="btn btn-primary mt-3">Start Shopping</a>
    </div>
<?php else: ?>
    <form method="POST" action="">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="assets/images/<?php echo htmlspecialchars($item['image'] ?? 'placeholder.jpg'); ?>"
                                        alt="<?php echo htmlspecialchars($item['name']); ?>"
                                        style="width: 50px; height: 50px; object-fit: cover;" class="me-3 rounded">
                                    <a href="product_details.php?id=<?php echo $item['id']; ?>"
                                        class="text-decoration-none fw-bold text-dark"><?php echo htmlspecialchars($item['name']); ?></a>
                                </div>
                            </td>
                            <td><?php echo formatPrice($item['final_price']); ?></td>
                            <td>
                                <input type="number" name="quantities[<?php echo $item['id']; ?>]"
                                    value="<?php echo $item['quantity']; ?>" min="1" class="form-control" style="width: 80px;">
                            </td>
                            <td class="fw-bold"><?php echo formatPrice($item['subtotal']); ?></td>
                            <td>
                                <button type="submit" name="remove_item" value="<?php echo $item['id']; ?>"
                                    class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="3" class="text-end fw-bold fs-5">Total:</td>
                        <td colspan="2" class="fw-bold fs-5 text-primary"><?php echo formatPrice($total_price); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="products.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Continue
                Shopping</a>
            <div>
                <button type="submit" name="update_cart" class="btn btn-secondary me-2">Update Cart</button>
                <a href="checkout.php" class="btn btn-primary btn-lg">Proceed to Checkout <i
                        class="fas fa-arrow-right ms-2"></i></a>
            </div>
        </div>
    </form>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>