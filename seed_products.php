<?php
require_once 'config/database.php';

try {
    // 1. Update existing products with new images
    $updates = [
        'iphone-15-pro' => 'iphone15.png',
        'samsung-galaxy-s24' => 's24.png',
        'men-t-shirt' => 'tshirt.png'
    ];

    foreach ($updates as $slug => $image) {
        $stmt = $pdo->prepare("UPDATE products SET image = ? WHERE slug = ?");
        $stmt->execute([$image, $slug]);
        echo "Updated image for $slug to $image<br>";
    }

    // 2. Insert new products
    $newProducts = [
        [
            'name' => 'Sony WH-1000XM5',
            'slug' => 'sony-wh-1000xm5',
            'description' => 'Industry-leading noise canceling headphones with Auto NC Optimizer, crystal clear hands-free calling, and up to 30-hour battery life.',
            'price' => 348.00,
            'discount' => 50.00,
            'category_id' => 1, // Electronics
            'stock' => 25,
            'image' => 'placeholder.jpg' // Placeholder due to rate limit
        ],
        [
            'name' => 'MacBook Air M2',
            'slug' => 'macbook-air-m2',
            'description' => 'Redesigned around the next-generation M2 chip, MacBook Air is strikingly thin and brings exceptional speed and power efficiency.',
            'price' => 1099.00,
            'discount' => 100.00,
            'category_id' => 1, // Electronics
            'stock' => 10,
            'image' => 'placeholder.jpg' // Placeholder due to rate limit
        ],
        [
            'name' => 'Nike Air Jordan 1',
            'slug' => 'nike-air-jordan-1',
            'description' => 'The Air Jordan 1 Mid brings full-court style and premium comfort to an iconic look.',
            'price' => 125.00,
            'discount' => 0.00,
            'category_id' => 2, // Fashion
            'stock' => 40,
            'image' => 'placeholder.jpg' // Placeholder due to rate limit
        ]
    ];

    $stmt = $pdo->prepare("INSERT INTO products (name, slug, description, price, discount, category_id, stock, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    foreach ($newProducts as $product) {
        // Check if product already exists to avoid duplicates
        $check = $pdo->prepare("SELECT id FROM products WHERE slug = ?");
        $check->execute([$product['slug']]);

        if ($check->rowCount() == 0) {
            $stmt->execute([
                $product['name'],
                $product['slug'],
                $product['description'],
                $product['price'],
                $product['discount'],
                $product['category_id'],
                $product['stock'],
                $product['image']
            ]);
            echo "Inserted new product: " . $product['name'] . "<br>";
        } else {
            echo "Product already exists: " . $product['name'] . "<br>";
        }
    }

    echo "<br><strong>Database update completed successfully!</strong>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>