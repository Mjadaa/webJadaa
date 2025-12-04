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

// Check DB connection if models loaded
if (db) {
    db.sequelize.authenticate()
        .then(() => {
            console.log('Connection has been established successfully.');
        })
        .catch(err => {
            console.error('Unable to connect to the database:', err);
        });
}

// set port, listen for requests
const PORT = process.env.PORT || 8080;
app.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}.`);
});
