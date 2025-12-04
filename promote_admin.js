require('dotenv').config();
const db = require('./models');

const username = process.argv[2];

if (!username) {
    console.log("Usage: node promote_admin.js <username>");
    process.exit(1);
}

async function promoteUser() {
    try {
        const config = require('./config/config.js')[process.env.NODE_ENV || 'development'];
        console.log(`Connecting to database at ${config.host}:${config.port || 3306}...`);

        const user = await db.User.findOne({ where: { username: username } });

        if (!user) {
            console.error(`❌ User '${username}' not found!`);
            console.log("---------------------------------------------------");
            console.log("Available users in this database:");
            const users = await db.User.findAll();
            if (users.length === 0) {
                console.log("   (No users found. Did you register?)");
            } else {
                users.forEach(u => console.log(` - ${u.username} (Role: ${u.role})`));
            }
            console.log("---------------------------------------------------");
            return;
        }

        user.role = 'admin';
        await user.save();

        console.log(`✅ SUCCESS: User '${username}' is now an ADMIN.`);
    } catch (error) {
        console.error("Error updating user:", error);
    } finally {
        await db.sequelize.close();
    }
}

promoteUser();
