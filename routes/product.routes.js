const express = require('express');
const router = express.Router();
const db = require('../models');
const Product = db.Product;
const Category = db.Category;

// GET all products
router.get('/', async (req, res) => {
    try {
        const products = await Product.findAll({
            include: [{ model: Category, attributes: ['name'] }]
        });
        res.json(products);
    } catch (err) {
        res.status(500).json({ message: err.message });
    }
});

// GET single product by slug
router.get('/:slug', async (req, res) => {
    try {
        const product = await Product.findOne({
            where: { slug: req.params.slug },
            include: [{ model: Category, attributes: ['name'] }]
        });
        if (!product) {
            return res.status(404).json({ message: 'Product not found' });
        }
        res.json(product);
    } catch (err) {
        res.status(500).json({ message: err.message });
    }
});

module.exports = router;
