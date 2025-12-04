const db = require('./models');

async function checkSchema() {
    try {
        console.log("Checking 'users' table schema...");
        const [results, metadata] = await db.sequelize.query("DESCRIBE users;");
        console.log("Columns in 'users' table:");
        results.forEach(col => {
            console.log(`- ${col.Field} (${col.Type})`);
        });
    } catch (error) {
        console.error("Error checking schema:", error);
    } finally {
        await db.sequelize.close();
    }
}

checkSchema();
