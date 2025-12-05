// Create Admin User Script
// Run this file with: node create_admin.js

const bcrypt = require('bcryptjs');
const db = require('./models');

async function createAdminUser() {
    try {
        // Connect to database
        await db.sequelize.authenticate();
        console.log('✓ Database connected');

        // Sync database (create tables if they don't exist)
        await db.sequelize.sync({ alter: true });
        console.log('✓ Database synced');

        // Admin credentials
        const adminData = {
            username: 'admin',
            email: 'admin@jadaaemart.com',
            password: 'Admin@123',
            full_name: 'Administrator',
            role: 'admin'
        };

        // Check if admin already exists
        const existingAdmin = await db.User.findOne({
            where: { username: adminData.username }
        });

        if (existingAdmin) {
            console.log('⚠ Admin user already exists!');
            console.log('Username:', adminData.username);
            console.log('Email:', existingAdmin.email);
            return;
        }

        // Hash password
        const hashedPassword = bcrypt.hashSync(adminData.password, 8);

        // Create admin user
        const admin = await db.User.create({
            username: adminData.username,
            email: adminData.email,
            password: hashedPassword,
            full_name: adminData.full_name,
            role: 'admin'
        });

        console.log('\n✅ Admin user created successfully!');
        console.log('==========================================');
        console.log('Username:', adminData.username);
        console.log('Email:', adminData.email);
        console.log('Password:', adminData.password);
        console.log('==========================================');
        console.log('\n📝 IMPORTANT: Save these credentials!');
        console.log('Login at: http://localhost:8080/login.html');
        console.log('Or deployed site: https://your-app.vercel.app/login.html');

        process.exit(0);
    } catch (error) {
        console.error('❌ Error creating admin:', error);
        process.exit(1);
    }
}

// Run the function
createAdminUser();
