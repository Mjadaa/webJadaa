'use strict';
const {
    Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
    class Product extends Model {
        static associate(models) {
            Product.belongsTo(models.Category, { foreignKey: 'category_id' });
        }
    }
    Product.init({
        name: {
            type: DataTypes.STRING,
            allowNull: false
        },
        slug: {
            type: DataTypes.STRING,
            allowNull: false,
            unique: true
        },
        description: DataTypes.TEXT,
        price: {
            type: DataTypes.DECIMAL(10, 2),
            allowNull: false
        },
        discount: {
            type: DataTypes.DECIMAL(10, 2),
            defaultValue: 0.00
        },
        stock: {
            type: DataTypes.INTEGER,
            defaultValue: 0
        },
        image: DataTypes.STRING,
        category_id: DataTypes.INTEGER
    }, {
        sequelize,
        modelName: 'Product',
        tableName: 'products',
        timestamps: true,
        createdAt: 'created_at',
        updatedAt: false
    });
    return Product;
};
