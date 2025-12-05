# Jadaa Emart - Modern E-commerce Platform

A comprehensive, bilingual (Arabic/English) e-commerce platform built with Node.js, Express, and MySQL, featuring a premium modern design with glassmorphism effects.

## 🌟 Features

### 🎨 **Modern Design**
- Premium CSS with glassmorphism effects
- Smooth animations and transitions
- Vibrant gradient backgrounds
- Fully responsive (mobile, tablet, desktop)
- Professional hover effects and micro-animations

### 🌍 **Bilingual Support**
- Full Arabic (RTL) and English (LTR) support
- One-click language switching
- Automatic layout direction switching
- All UI elements translated

### 🔐 **Security Features**
- JWT-based authentication
- Password show/hide toggle
- Secure password hashing with bcrypt
- Role-based access control (Admin/Customer)
- Input validation on client and server

### 🛍️ **E-commerce Functionalities**
- Product browsing with categories
- Search and filter products
- Shopping cart management
- Order checkout and history
- Admin panel for product management
- Category management
- User profile management

## 🚀 Quick Start

### Prerequisites
- Node.js (v14 or higher)
- MySQL or MariaDB
- XAMPP (for local development) or cloud database

### Installation

1. **Clone the repository**
```bash
cd c:\xampp\htdocs\JadaaMart
```

2. **Install dependencies**
```bash
npm install
```

3. **Configure environment variables**
```bash
# Copy .env.example to .env
copy .env.example .env

# Edit .env with your database credentials
```

4. **Start MySQL (if using XAMPP)**
- Start Apache and MySQL from XAMPP Control Panel

5. **Run the application**
```bash
npm start
```

6. **Access the application**
- Open browser: `http://localhost:8080`

## 📦 Project Structure

```
JadaaMart/
├── config/              # Database and auth configuration
├── controllers/         # Request handlers
├── middleware/          # Authentication middleware
├── models/             # Sequelize models
├── public/            # Frontend files
│   ├── css/           # Stylesheets
│   │   └── styles.css    # Main premium CSS
│   ├── js/            # JavaScript files
│   │   └── i18n.js       # Internationalization
│   ├── lang/          # Translation files
│   │   ├── en.json       # English translations
│   │   └── ar.json       # Arabic translations
│   ├── index.html     # Homepage
│   ├── login.html     # Login page
│   ├── register.html  # Registration page
│   ├── admin.html     # Admin panel
│   └── profile.html   # User profile
├── routes/            # API routes
├── server.js          # Main application file
├── vercel.json        # Vercel deployment config
└── package.json       # Dependencies
```

## 🔧 API Endpoints

### Authentication
- `POST /api/auth/register` - Register new user
- `POST /api/auth/login` - Login user

### Products
- `GET /api/products` - Get all products
- `GET /api/products/:slug` - Get single product
- `POST /api/products` - Create product (Admin only)
- `PUT /api/products/:id` - Update product (Admin only)
- `DELETE /api/products/:id` - Delete product (Admin only)

### Categories
- `GET /api/products/categories` - Get all categories

### Cart
- `GET /api/products/cart` - Get user cart
- `POST /api/products/cart` - Add to cart
- `DELETE /api/products/cart/:id` - Remove from cart

### Orders
- `GET /api/products/orders` - Get user orders
- `POST /api/products/checkout` - Checkout cart

## 🎯 Initial Setup

### Create First Admin User

1. Register a normal user through `/register.html`
2. Visit: `http://localhost:8080/api/promote?username=yourusername`
3. This will promote your user to admin role

### Seed Sample Data

Visit: `http://localhost:8080/api/seed`

This will create sample categories and products.

## 🌐 Language Switching

The language toggle button appears in the header on all pages:
- Click "🇸🇦 العربية" to switch to Arabic (RTL layout)
- Click "🇺🇸 English" to switch to English (LTR layout)
- Language preference is saved in browser localStorage

## 📱 Responsive Breakpoints

- **Desktop**: 1024px and above
- **Tablet**: 768px - 1023px
- **Mobile**: Below 768px

## 🎨 Design System

### Colors
- **Primary**: Purple gradient (#667eea to #764ba2)
- **Secondary**: Pink gradient (#f093fb to #f5576c)
- **Background**: Dark theme (#0f172a)
- **Accents**: Cyan, Pink, Orange, Green

### Typography
- **Primary Font**: Outfit (English)
- **Arabic Font**: Cairo, Tajawal
- **Font Sizes**: Responsive using clamp()

## 🚀 Deployment to Vercel

### Method 1: GitHub Integration (Recommended)

1. **Push to GitHub**
```bash
git add .
git commit -m "Complete bilingual e-commerce platform"
git push origin main
```

2. **Connect to Vercel**
- Go to [Vercel Dashboard](https://vercel.com)
- Click "New Project"
- Import your GitHub repository
- Vercel will auto-detect Node.js project

3. **Configure Environment Variables**
- In Vercel Dashboard > Settings > Environment Variables
- Add all variables from `.env.example`:
  - `NODE_ENV=production`
  - `DB_HOST=your-cloud-db-host`
  - `DB_USERNAME=your-db-username`
  - `DB_PASSWORD=your-db-password`
  - `DB_NAME=jadaamart_db`
  - `DB_PORT=3306`
  - `JWT_SECRET=your-secure-jwt-secret`

4. **Deploy**
- Click "Deploy"
- Vercel will build and deploy automatically
- Your site will be live at `https://your-project.vercel.app`

### Method 2: Vercel CLI

```bash
npm install -g vercel
vercel login
vercel
```

## 🗄️ Database Setup for Production

### Option 1: Vercel Postgres
```bash
vercel postgres create
```

### Option 2: External MySQL/MariaDB
- Use services like PlanetScale, AWS RDS, or Railway
- Update environment variables in Vercel

### Sync Database Tables
After deployment, visit:
```
https://your-app.vercel.app/api/sync
```

## 🔒 Security Best Practices

1. **Never commit `.env` file**
2. **Use strong JWT secrets**
3. **Enable HTTPS in production** (Vercel does this automatically)
4. **Validate all user inputs**
5. **Use prepared statements** (Sequelize does this)
6. **Implement rate limiting** (future enhancement)

## 🐛 Troubleshooting

### Database Connection Issues
1. Check environment variables in Vercel
2. Verify database host allows external connections
3. Check database credentials
4. Visit `/api/debug-env` to see configuration

### Static Files Not Loading
- Ensure `vercel.json` is properly configured
- Check file paths are relative to `public/` directory

### Language Not Switching
- Clear browser cache
- Check browser console for errors
- Verify translation files (`en.json`, `ar.json`) are accessible

## 📝 License

ISC

## 👨‍💻 Author

Built for modern e-commerce needs with bilingual support.

## 🙏 Acknowledgments

- Modern CSS design inspired by glassmorphism trends
- i18n implementation for seamless bilingual experience
- Vercel for serverless deployment
