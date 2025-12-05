const { verifySignUp } = require("../middleware");
const controller = require("../controllers/auth.controller");

module.exports = function (app) {
    app.use(function (req, res, next) {
        res.header(
            "Access-Control-Allow-Headers",
            "x-access-token, Origin, Content-Type, Accept"
        );
        next();
    });

    app.post(
        "/api/auth/signup",
        controller.signup
    );

    // Add register as alias for signup (for frontend compatibility)
    app.post(
        "/api/auth/register",
        controller.signup
    );

    app.post("/api/auth/signin", controller.signin);

    // Add login as alias for signin (for frontend compatibility)
    app.post("/api/auth/login", controller.signin);
};
