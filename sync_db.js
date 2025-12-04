const db = require('./models');

async function resetDatabase() {
    try {
        console.log("Disabling foreign key checks...");
        await db.sequelize.query('SET FOREIGN_KEY_CHECKS = 0', { raw: true });

        console.log("Forcing database sync (dropping all tables)...");
        await db.sequelize.sync({ force: true });

        console.log("Enabling foreign key checks...");
        await db.sequelize.query('SET FOREIGN_KEY_CHECKS = 1', { raw: true });

        console.log("Database reset complete! All tables recreated with correct schema.");
    } catch (error) {
        console.error("Error resetting database:", error);
    } finally {
        await db.sequelize.close();
    }
}

resetDatabase();
