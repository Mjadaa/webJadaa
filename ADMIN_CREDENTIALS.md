# 🔐 Admin Credentials - Jadaa Emart

## Default Admin Account

After deploying to Vercel, use these steps to create your admin account:

### Method 1: Direct Database Creation (Recommended)

After deployment, run the admin creation script on Vercel or your local database:

```bash
node create_admin.js
```

This will create:
- **Username**: `admin`
- **Email**: `admin@jadaaemart.com`
- **Password**: `Admin@123`
- **Role**: `admin`

---

### Method 2: Register and Promote

1. **Register a normal user** at:
   - Local: `http://localhost:8080/register.html`
   - Vercel: `https://your-app.vercel.app/register.html`

2. **Use any credentials you want**, for example:
   - Username: `mohammed`
   - Email: `mohammed@example.com`
   - Password: `YourPassword123`

3. **Promote to admin** by visiting:
   - Local: `http://localhost:8080/api/promote?username=mohammed`
   - Vercel: `https://your-app.vercel.app/api/promote?username=mohammed`

---

## 🎯 Quick Login

### After creating admin (Method 1 or 2):

**Login Page**: 
- Local: `http://localhost:8080/login.html`
- Vercel: `https://your-app.vercel.app/login.html`

**Credentials Option 1** (if you ran create_admin.js):
```
Username: admin
Password: Admin@123
```

**Credentials Option 2** (if you self-registered):
```
Username: [your-chosen-username]
Password: [your-chosen-password]
```

---

## ✅ What Was Fixed

1. **API Endpoint Mismatch**: 
   - Added `/api/auth/register` endpoint (was only `/api/auth/signup`)
   - Added `/api/auth/login` endpoint (was only `/api/auth/signin`)
   
2. **Registration Error**:
   - Made `full_name` optional (uses username if not provided)
   - Now registration works properly from frontend

3. **Role Missing**:
   - Added `role` field to login response
   - Frontend now knows if user is admin or regular user

4. **Admin Creation**:
   - Created `create_admin.js` script for direct admin creation

---

## 📝 Important Notes

- **Change the default password** after first login!
- The admin panel is at: `/admin.html`
- After login as admin, you'll be redirected automatically
- You can create more products, categories, etc. from admin panel

---

## 🚀 Vercel Deployment

If your app is already on Vercel:
1. The updates will deploy automatically from GitHub
2. Wait 2-3 minutes for deployment
3. Visit `/api/sync` to create database tables
4. Visit `/api/seed` to add sample data (optional)
5. Register an account at `/register.html`
6. Visit `/api/promote?username=yourname` to make yourself admin

---

**Your site is ready to use!** 🎉
