<?php
require_once 'config/database.php';

// Set the new password here
$new_password = 'admin123';
$email = 'admin@jadaamart.com';

// Hash the password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->execute([$hashed_password, $email]);

    if ($stmt->rowCount() > 0) {
        echo "Password updated successfully for user: $email<br>";
        echo "New Password: $new_password";
    } else {
        echo "User not found or password is already the same.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>