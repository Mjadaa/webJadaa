require('dotenv').config();
const db = require('./models');

const username = process.argv[2];

if (!username) {
    console.log("Usage: node promote_admin.js <username>");
    process.exit(1);
}

async function promoteUser() {
    try {
        console.log(`Connecting to database...`);
        const user = await db.User.findOne({ where: { username: username } });

        if (!user) {
            console.error(`User '${username}' not found!`);
            return;
        }

        user.role = 'admin';
        await user.save();

        console.log(`SUCCESS: User '${username}' is now an ADMIN.`);
    } catch (error) {
        console.error("Error updating user:", error);
    } finally {
        await db.sequelize.close();
    }
}

promoteUser();
