'use strict';
const {
    Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
    class Cart extends Model {
        static associate(models) {
            Cart.belongsTo(models.User, { foreignKey: 'user_id' });
            Cart.hasMany(models.CartItem, { foreignKey: 'cart_id' });
        }
    }
    Cart.init({
        user_id: {
            type: DataTypes.INTEGER,
            allowNull: true // Can be null for guest carts if implemented
        }
    }, {
        sequelize,
        modelName: 'Cart',
        tableName: 'cart',
        timestamps: true,
        createdAt: 'created_at',
        updatedAt: false
    });
    return Cart;
};
