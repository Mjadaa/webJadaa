// Simple Admin Creator - No dependencies on database connection
// This creates SQL statements you can run directly in phpMyAdmin or database

const bcrypt = require('bcryptjs');

// Admin credentials
const username = 'admin';
const password = 'Admin@123';
const email = 'admin@jadaaemart.com';
const full_name = 'Administrator';
const role = 'admin';

// Hash the password
const hashedPassword = bcrypt.hashSync(password, 8);

console.log('\n========================================');
console.log('🔐 ADMIN ACCOUNT CREDENTIALS');
console.log('========================================');
console.log('Username: ' + username);
console.log('Password: ' + password);
console.log('Email: ' + email);
console.log('Role: ' + role);
console.log('========================================\n');

console.log('📋 SQL Query to create admin (run in phpMyAdmin):');
console.log('========================================');
console.log(`
INSERT INTO users (username, email, password, full_name, role, created_at) 
VALUES (
  '${username}', 
  '${email}', 
  '${hashedPassword}', 
  '${full_name}', 
  '${role}', 
  NOW()
);
`);
console.log('========================================\n');

console.log('✅ Copy the SQL above and run it in phpMyAdmin');
console.log('   OR start XAMPP MySQL and run: node create_admin.js\n');
