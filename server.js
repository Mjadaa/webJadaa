const express = require('express');
const cors = require('cors');
const app = express();

var corsOptions = {
    origin: "*" // Allow all for debugging
};

app.use(cors(corsOptions));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

const path = require('path');

// Serve static files from 'public' directory
app.use(express.static(path.join(__dirname, 'public')));

let db;
let startupError = null;

try {
    db = require('./models');
} catch (err) {
    console.error("Failed to load models:", err);
    startupError = err;
}

// Routes
if (!startupError) {
    try {
        app.use('/api/products', require('./routes/product.routes'));
        require('./routes/auth.routes')(app); // Register Auth Routes
    } catch (err) {
        console.error("Failed to load routes:", err);
        startupError = err;
    }
}

// simple route
app.get("/", (req, res) => {
    if (startupError) {
        return res.status(500).json({
            message: "Server started with errors",
            error: startupError.message,
            stack: startupError.stack
        });
    }
    // Serve the index.html file
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

app.get("/login.html", (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'login.html'));
});

app.get("/register.html", (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'register.html'));
});

app.get("/admin.html", (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'admin.html'));
});

// Debug route to check env vars
app.get("/api/debug-env", (req, res) => {
    res.json({
        node_env: process.env.NODE_ENV,
        db_host: process.env.DB_HOST ? "Set" : "Not Set",
        db_user: process.env.DB_USER ? "Set" : "Not Set",
        db_username: process.env.DB_USERNAME ? "Set" : "Not Set",
        db_pass: process.env.DB_PASSWORD ? "Set" : "Not Set",
        db_name: process.env.DB_NAME ? "Set" : "Not Set",
        db_port: process.env.DB_PORT ? "Set" : "Not Set",
        startup_error: startupError ? startupError.message : "None"
    });
});

// Manual Sync Route (to fix missing tables)
app.get("/api/sync", async (req, res) => {
    if (!db) return res.status(500).json({ message: "Database not loaded" });
    try {
        // Reverted to alter: true to prevent accidental data loss
        await db.sequelize.sync({ alter: true });
        res.json({ message: "Database synced successfully! Schema updated." });
    } catch (err) {
        res.status(500).json({ message: "Sync failed", error: err.message });
    }
});

// Seeding Route (Populate Data)
app.get("/api/seed", async (req, res) => {
    if (!db) return res.status(500).json({ message: "Database not loaded" });
    try {
        const Category = db.Category;
        const Product = db.Product;

        // Check if data exists
        const count = await Product.count();
        if (count > 0) return res.json({ message: "Database already has data." });

        // Create Categories
        const electronics = await Category.create({ name: 'Electronics', description: 'Gadgets and devices' });
        const fashion = await Category.create({ name: 'Fashion', description: 'Clothing and accessories' });

        // Create Products
        await Product.bulkCreate([
            {
                name: 'iPhone 15 Pro',
                slug: 'iphone-15-pro',
                description: 'The ultimate iPhone.',
                price: 999.00,
                stock: 10,
                category_id: electronics.id,
                image: 'iphone15.png'
            },
            {
                name: 'Samsung Galaxy S24',
                slug: 'samsung-galaxy-s24',
                description: 'Galaxy AI is here.',
                price: 899.00,
                stock: 15,
                category_id: electronics.id,
                image: 's24.png'
            },
            {
                name: 'Men T-Shirt',
                slug: 'men-t-shirt',
                description: 'Cotton t-shirt.',
                price: 25.00,
                stock: 50,
                category_id: fashion.id,
                image: 'tshirt.png'
            }
        ]);

        res.json({ message: "Database seeded successfully!" });
    } catch (err) {
        res.status(500).json({ message: "Seeding failed", error: err.message });
    }
});

// Check DB connection if models loaded
// Sync DB and create tables if missing
if (db) {
    db.sequelize.sync({ alter: true })
        .then(() => {
            console.log('Database synced successfully.');
        })
        .catch(err => {
            console.error('Failed to sync database:', err);
        });
}

// set port, listen for requests
const PORT = process.env.PORT || 8080;
app.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}.`);
});
