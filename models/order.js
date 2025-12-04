'use strict';
const {
    Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
    class Order extends Model {
        static associate(models) {
            Order.belongsTo(models.User, { foreignKey: 'user_id' });
            Order.hasMany(models.OrderItem, { foreignKey: 'order_id' });
        }
    }
    Order.init({
        user_id: {
            type: DataTypes.INTEGER,
            allowNull: false
        },
        total_amount: {
            type: DataTypes.DECIMAL(10, 2),
            allowNull: false
        },
        payment_method: {
            type: DataTypes.STRING,
            defaultValue: 'cod'
        },
        status: {
            type: DataTypes.ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled'),
            defaultValue: 'pending'
        }
    }, {
        sequelize,
        modelName: 'Order',
        tableName: 'orders',
        timestamps: true,
        createdAt: 'created_at',
        updatedAt: false
    });
    return Order;
};
