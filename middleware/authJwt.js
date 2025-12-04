const jwt = require("jsonwebtoken");
const config = require("../config/auth.config.js");

verifyToken = (req, res, next) => {
    let token = req.headers["x-access-token"];

    if (!token) {
        return res.status(403).send({
            message: "No token provided!"
        });
    }

    jwt.verify(token, config.secret, (err, decoded) => {
        if (err) {
            return res.status(401).send({
                message: "Unauthorized!"
            });
        }
        req.userId = decoded.id;
        next();
    });
};



isAdmin = (req, res, next) => {
    // For simplicity in this demo, we'll check if username is 'admin'
    // In a real app, you'd query the User/Role table
    const db = require("../models");
    const User = db.User;

    User.findByPk(req.userId).then(user => {
        if (user && user.username === "admin") {
            next();
            return;
        }
        res.status(403).send({ message: "Require Admin Role!" });
    });
};

const authJwt = {
    verifyToken: verifyToken,
    isAdmin: isAdmin
};
module.exports = authJwt;
