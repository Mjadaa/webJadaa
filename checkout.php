<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

if (empty($_SESSION['cart'])) {
    redirect('cart.php');
}

// Handle Discount Code
$discount_code = '';
$discount_amount = 0;
$discount_percent = 0;

if (isset($_POST['apply_discount'])) {
    $code = strtoupper(trim($_POST['discount_code']));
    if ($code === 'JADAA30') {
        $_SESSION['discount_code'] = 'JADAA30';
        $_SESSION['discount_percent'] = 30;
        $success_msg = "Coupon code applied successfully!";
    } else {
        $error_msg = "Invalid coupon code.";
        unset($_SESSION['discount_code']);
        unset($_SESSION['discount_percent']);
    }
}

if (isset($_SESSION['discount_code']) && $_SESSION['discount_code'] === 'JADAA30') {
    $discount_code = 'JADAA30';
    $discount_percent = 30;
}

// Calculate Total
$subtotal = 0;
$cart_products = [];
if (isset($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
    $products = $stmt->fetchAll();
    foreach ($products as $product) {
        $qty = $_SESSION['cart'][$product['id']];
        $subtotal += $product['final_price'] * $qty;
        $cart_products[$product['id']] = [
            'price' => $product['final_price'],
            'qty' => $qty
        ];
    }
}

$discount_amount = ($subtotal * $discount_percent) / 100;
$total_amount = $subtotal - $discount_amount;


$error = $error_msg ?? '';
$success = $success_msg ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    $country = sanitize($_POST['country']);
    $city = sanitize($_POST['city']);
    $street = sanitize($_POST['street']);
    $payment_method = $_POST['payment_method'];

    if (empty($country) || empty($city) || empty($street)) {
        $error = 'Please fill in all shipping details.';
    } else {
        try {
            $pdo->beginTransaction();

            // 1. Create Order
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, payment_method, status) VALUES (?, ?, ?, 'pending')");
            $stmt->execute([$_SESSION['user_id'], $total_amount, $payment_method]);
            $order_id = $pdo->lastInsertId();

            // 2. Create Order Items
            $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($cart_products as $pid => $data) {
                $stmt_item->execute([$order_id, $pid, $data['qty'], $data['price']]);
            }

            // 3. Save Address (Optional)
            $stmt_addr = $pdo->prepare("INSERT INTO addresses (user_id, country, city, street) VALUES (?, ?, ?, ?)");
            $stmt_addr->execute([$_SESSION['user_id'], $country, $city, $street]);

            // 4. Clear Cart and Discount
            unset($_SESSION['cart']);
            unset($_SESSION['discount_code']);
            unset($_SESSION['discount_percent']);

            $pdo->commit();
            $success = "Order placed successfully! Order ID: #$order_id";
            // Redirect to profile after 2 seconds
            header("refresh:2;url=profile.php");
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Order failed: " . $e->getMessage();
        }
    }
}

include 'includes/header.php';
?>

<div class="row">
    <div class="col-md-8">
        <h2 class="mb-4 fw-bold text-primary">Checkout</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if (!isset($order_id)): // Hide form if order placed ?>

            <form method="POST" action="">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white fw-bold">Shipping Address</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Street Address</label>
                            <input type="text" name="street" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white fw-bold">Payment Method</div>
                    <div class="card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" value="cod" checked>
                            <label class="form-check-label">Cash on Delivery (COD)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" value="card" disabled>
                            <label class="form-check-label text-muted">Credit Card (Coming Soon)</label>
                        </div>
                    </div>
                </div>

                <button type="submit" name="place_order" class="btn btn-primary btn-lg w-100">Place Order</button>
            </form>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Discount Code</div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="input-group">
                        <input type="text" name="discount_code" class="form-control" placeholder="Enter code"
                            value="<?php echo htmlspecialchars($discount_code); ?>">
                        <button class="btn btn-outline-secondary" type="submit" name="apply_discount">Apply</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">Order Summary</div>
            <div class="card-body">
                <ul class="list-group list-group-flush mb-3">
                    <?php foreach ($cart_products as $pid => $data):
                        $p_name = "Product #$pid";
                        foreach ($products as $p) {
                            if ($p['id'] == $pid)
                                $p_name = $p['name'];
                        }
                        ?>
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                                <h6 class="my-0"><?php echo htmlspecialchars($p_name); ?></h6>
                                <small class="text-muted">Qty: <?php echo $data['qty']; ?></small>
                            </div>
                            <span class="text-muted"><?php echo formatPrice($data['price'] * $data['qty']); ?></span>
                        </li>
                    <?php endforeach; ?>

                    <li class="list-group-item d-flex justify-content-between bg-light">
                        <span>Subtotal</span>
                        <strong><?php echo formatPrice($subtotal); ?></strong>
                    </li>
                    <?php if ($discount_amount > 0): ?>
                        <li class="list-group-item d-flex justify-content-between bg-light text-success">
                            <span>Discount (<?php echo $discount_percent; ?>%)</span>
                            <strong>-<?php echo formatPrice($discount_amount); ?></strong>
                        </li>
                    <?php endif; ?>
                    <li class="list-group-item d-flex justify-content-between bg-light">
                        <span class="fw-bold">Total (USD)</span>
                        <strong class="text-primary"><?php echo formatPrice($total_amount); ?></strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>