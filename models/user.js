'use strict';
const {
    Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
    class User extends Model {
        static associate(models) {
            // define association here
            User.hasMany(models.Order, { foreignKey: 'user_id' });
            User.hasOne(models.Cart, { foreignKey: 'user_id' });
        }
    }
    User.init({
        full_name: {
            type: DataTypes.STRING,
            allowNull: false
        },
        username: {
            type: DataTypes.STRING,
            allowNull: false,
            unique: true
        },
        email: {
            type: DataTypes.STRING,
            allowNull: false,
            unique: true
        },
        password: {
            type: DataTypes.STRING,
            allowNull: false
        },
        phone: DataTypes.STRING,
        role: {
            type: DataTypes.ENUM('admin', 'user'),
            defaultValue: 'user'
        }
    }, {
        sequelize,
        modelName: 'User',
        tableName: 'users',
        timestamps: true,
        createdAt: 'created_at',
        updatedAt: false // Schema doesn't have updated_at
    });
    return User;
};
