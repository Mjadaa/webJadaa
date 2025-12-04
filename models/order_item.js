'use strict';
const {
    Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
    class OrderItem extends Model {
        static associate(models) {
            OrderItem.belongsTo(models.Order, { foreignKey: 'order_id' });
            OrderItem.belongsTo(models.Product, { foreignKey: 'product_id' });
        }
    }
    OrderItem.init({
        order_id: {
            type: DataTypes.INTEGER,
            allowNull: false
        },
        product_id: DataTypes.INTEGER,
        quantity: {
            type: DataTypes.INTEGER,
            allowNull: false
        },
        price: {
            type: DataTypes.DECIMAL(10, 2),
            allowNull: false
        }
    }, {
        sequelize,
        modelName: 'OrderItem',
        tableName: 'order_items',
        timestamps: false
    });
    return OrderItem;
};
