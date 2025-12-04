const express = require('express');
const cors = require('cors');
const app = express();

var corsOptions = {
    origin: "*" // Allow all for debugging
};

app.use(cors(corsOptions));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

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
    res.json({ message: "Welcome to JadaaMart API." });
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
        // Using force: true to drop tables and recreate them (Fixes constraint errors)
        await db.sequelize.sync({ force: true });
        res.json({ message: "Database synced successfully! Tables recreated." });
    } catch (err) {
        res.status(500).json({ message: "Sync failed", error: err.message });
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
