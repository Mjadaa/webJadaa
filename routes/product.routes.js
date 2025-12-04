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

const { authJwt } = require("../middleware");

// POST create new product (Admin only)
router.post('/', [authJwt.verifyToken, authJwt.isAdmin], async (req, res) => {
    try {
        const product = await Product.create(req.body);
        res.status(201).json(product);
    } catch (err) {
        res.status(500).json({ message: err.message });
    }
});

// PUT update product (Admin only)
router.put('/:id', [authJwt.verifyToken, authJwt.isAdmin], async (req, res) => {
    try {
        const id = req.params.id;
        const [num] = await Product.update(req.body, { where: { id: id } });
        if (num == 1) {
            res.send({ message: "Product was updated successfully." });
        } else {
            res.send({ message: `Cannot update Product with id=${id}. Maybe Product was not found or req.body is empty!` });
        }
    } catch (err) {
        res.status(500).json({ message: err.message });
    }
});

// DELETE product (Admin only)
router.delete('/:id', [authJwt.verifyToken, authJwt.isAdmin], async (req, res) => {
    try {
        const id = req.params.id;
        const num = await Product.destroy({ where: { id: id } });
        if (num == 1) {
            res.send({ message: "Product was deleted successfully!" });
        } else {
            res.send({ message: `Cannot delete Product with id=${id}. Maybe Product was not found!` });
        }
    } catch (err) {
        res.status(500).json({ message: err.message });
    }
});

module.exports = router;
