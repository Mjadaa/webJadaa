const db = require('./models');

async function sync() {
    try {
        console.log("Attempting to force sync User table...");
        // force: true will DROP the table and recreate it, removing the data causing the conflict
        await db.User.sync({ force: true });
        console.log("User table recreated successfully! The 'username' column is now present and correct.");
    } catch (error) {
        console.error("Error syncing database:", error);
    } finally {
        await db.sequelize.close();
    }
}

sync();
