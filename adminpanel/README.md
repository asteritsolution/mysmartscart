# KRC Woollens Admin Panel

Complete professional admin panel for managing the KRC Woollens e-commerce website.

## Login Credentials

**URL:** `http://localhost/krcwoollen/adminpanel/`

**Email:** `admin@krcwoollens.com`  
**Password:** `admin123`

## Features

### 1. Dashboard
- Statistics overview (Products, Orders, Revenue, Users)
- Recent orders list
- Quick access to all sections

### 2. Products Management
- View all products with images
- Add new products
- Edit existing products
- Delete products
- Set featured, best selling, top rated flags
- Manage stock and pricing

### 3. Categories Management
- View all categories
- Add new categories
- Edit categories
- Delete categories
- Set sort order and status

### 4. Orders Management
- View all orders
- Update order status (Pending, Processing, Shipped, Delivered, Cancelled)
- View detailed order information
- Customer details and shipping address

### 5. Banners Management
- Manage homepage slider banners
- Upload banner images
- Set banner links and sort order
- Enable/disable banners

### 6. Users Management
- View all registered users
- Activate/deactivate users
- View user order count
- User registration details

### 7. Contact Messages
- View all contact form submissions
- Mark messages as read/unread
- View message details
- Delete messages

### 8. Settings
- Update contact information
- Set business hours
- Configure map coordinates for contact page
- Site-wide settings management

## File Structure

```
adminpanel/
├── index.php              # Admin login page
├── dashboard.php          # Main dashboard
├── config.php            # Admin configuration
├── logout.php            # Logout handler
├── products.php          # Products list
├── product-add.php       # Add product
├── product-edit.php      # Edit product
├── categories.php        # Categories list
├── category-add.php      # Add category
├── category-edit.php     # Edit category
├── orders.php            # Orders list
├── order-details.php     # Order details view
├── banners.php           # Banners list
├── banner-add.php        # Add banner
├── banner-edit.php       # Edit banner
├── users.php             # Users list
├── contact-messages.php  # Contact messages list
├── message-view.php      # View message details
├── settings.php          # Site settings
└── includes/
    ├── header.php        # Common header
    └── footer.php        # Common footer
```

## Security

- Admin authentication required for all pages
- Only admin email (`admin@krcwoollens.com`) can access
- Session-based authentication
- SQL injection protection with prepared statements
- XSS protection with htmlspecialchars

## Design Features

- Modern gradient design
- Responsive layout (mobile-friendly)
- DataTables for sortable/searchable tables
- Bootstrap 4 framework
- Font Awesome icons
- Professional color scheme
- Smooth animations and transitions

## Notes

- All images are uploaded to appropriate directories
- Product images: `assets/images/products/`
- Banner images: `assets/images/banners/`
- Placeholder images are used when no image is uploaded
- All changes are immediately reflected on the frontend

