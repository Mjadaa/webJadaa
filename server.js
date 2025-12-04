const express = require('express');
const cors = require('cors');
const db = require('./models');

const app = express();

var corsOptions = {
    origin: "*" // Allow all for debugging
};

app.use(cors(corsOptions));

// parse requests of content-type - application/json
app.use(express.json());

// parse requests of content-type - application/x-www-form-urlencoded
app.use(express.urlencoded({ extended: true }));

// Routes
app.use('/api/products', require('./routes/product.routes'));

// simple route
app.get("/", (req, res) => {
    res.json({ message: "Welcome to JadaaMart API." });
});

// Check DB connection
db.sequelize.authenticate()
    .then(() => {
        console.log('Connection has been established successfully.');
    })
    .catch(err => {
        console.error('Unable to connect to the database:', err);
    });

// set port, listen for requests
const PORT = process.env.PORT || 8080;
app.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}.`);
});
