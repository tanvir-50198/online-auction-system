# Online Auction System

A full-stack web-based auction platform built with **PHP, MySQL, JavaScript, AJAX, and MVC architecture**. Buyers can browse auctions and place bids, sellers can manage listings, and administrators can manage users, listings, and categories.

<p align="center">
  <a href="https://onlineauctiontanvir.infy.click"><strong>🌐 Live Demo: onlineauctiontanvir.infy.click</strong></a>
</p>

---

## 📸 Application Screenshots

Add your actual application screenshots to the `screenshots/` folder using the filenames below. Each image path is a placeholder until you add the matching screenshot file.

**Recommended screenshot format:** PNG or JPG. Capture the page with useful content visible and avoid including passwords, private user information, or other sensitive data.

### 🏠 Common & Authentication Pages

| Page | Screenshot |
|---|---|
| Home / Landing Page | ![Home Page](screenshots/common/home.png) |
| Login Page | ![Login Page](screenshots/common/login.png) |
| Registration Page | ![Registration Page](screenshots/common/register.png) |

### 👤 Buyer Pages

| Page | Screenshot |
|---|---|
| Buyer Dashboard | ![Buyer Dashboard](screenshots/buyer/dashboard.png) |
| Browse Auctions | ![Browse Auctions](screenshots/buyer/browse-auctions.png) |
| Auction Details | ![Auction Details](screenshots/buyer/auction-details.png) |
| Place Bid | ![Place Bid](screenshots/buyer/place-bid.png) |
| My Bids / Bid Status | ![My Bids](screenshots/buyer/my-bids.png) |
| Watchlist | ![Watchlist](screenshots/buyer/watchlist.png) |
| Buyer Profile | ![Buyer Profile](screenshots/buyer/profile.png) |


### 🧑‍💼 Seller Pages

| Page | Screenshot |
|---|---|
| Seller Dashboard | ![Seller Dashboard](screenshots/seller/dashboard.png) |
| Create Auction Listing | ![Create Listing](screenshots/seller/create-listing.png) |
| Manage Listings | ![Manage Listings](screenshots/seller/manage-listings.png) |
| Edit Listing | ![Edit Listing](screenshots/seller/edit-listing.png) |
| Live Bids / Bid Monitoring | ![Seller Bids](screenshots/seller/bids.png) |
| Auction Results | ![Auction Results](screenshots/seller/results.png) |
| Seller Profile | ![Seller Profile](screenshots/seller/profile.png) |
### 👑 Admin Pages

| Page | Screenshot |
|---|---|
| Admin Dashboard | ![Admin Dashboard](screenshots/admin/dashboard.png) |
| User Management | ![User Management](screenshots/admin/users.png) |
| Auction Listing Management | ![Listing Management](screenshots/admin/listings.png) |
| Category Management | ![Category Management](screenshots/admin/categories.png) |

| Admin Profile | ![Admin Profile](screenshots/admin/profile.png) |
| Activate / Deactivate Account | ![Account Status Management](screenshots/admin/account-status.png) |
| Platform Statistics | ![Platform Statistics](screenshots/admin/statistics.png) |

### ⚡ AJAX & Important UI States

| Feature / State | Screenshot |
|---|---|
| AJAX Auction Search | ![AJAX Search](screenshots/features/ajax-search.png) |
| AJAX Category Management | ![AJAX Categories](screenshots/features/ajax-categories.png) |
| Updated Bid Status | ![Updated Bid Status](screenshots/features/bid-status-update.png) |
| Auction Countdown Timer | ![Countdown Timer](screenshots/features/countdown-timer.png) |
| Form Validation / Error Message | ![Form Validation](screenshots/features/form-validation.png) |
| Empty Watchlist / No Results | ![Empty State](screenshots/features/empty-state.png) |
| Mobile / Responsive View | ![Responsive View](screenshots/features/responsive-view.png) |

> **Important:** These image paths are prepared slots, not screenshots included with this README. Add your own screenshot files with the exact filenames above. If a page or feature is not present in your application, remove that row rather than adding a misleading screenshot.

---

## ✨ Features

### 👤 Buyer
- Register and log in
- Browse active auction listings
- Search and filter auctions with AJAX
- View auction details and countdowns
- Place bids
- Manage a watchlist and track bids
- Manage profile information and profile picture
- Review sellers

### 🧑‍💼 Seller
- Register and log in
- Create auction listings and upload product images
- Edit listings where permitted and delete listings
- Manage listings and monitor bids
- View auction results
- Review winning buyers

### 👑 Administrator
- View the admin dashboard
- Manage users and account status
- Manage auction listings
- Manage categories
- Create administrator accounts
- Monitor platform activity

## 🛠️ Technology Stack

| Technology | Purpose |
|---|---|
| PHP | Server-side application logic |
| MySQL | Database storage |
| HTML5 | Page structure |
| CSS3 | Styling and layout |
| JavaScript | Client-side interactions |
| AJAX | Asynchronous search and updates |
| MVC Architecture | Separation of application responsibilities |
| XAMPP | Local development environment |
| Git & GitHub | Version control |

## 🏗️ Architecture

The application follows an MVC-style organization:

- **Models** — database operations and data access
- **Views** — pages and user interface
- **Controllers** — request handling and application logic
- **AJAX endpoints** — asynchronous actions such as auction search, bid updates, and category management

## 📂 Project Structure

```text
auction-system/
├── ajax/
├── assets/
│   ├── css/
│   ├── images/
│   └── js/
├── config/
├── controllers/
├── database/
├── includes/
├── models/
├── uploads/
│   ├── listings/
│   └── profiles/
├── views/
│   ├── admin/
│   ├── buyer/
│   ├── partials/
│   └── seller/
├── screenshots/
│   ├── common/
│   ├── buyer/
│   ├── seller/
│   ├── admin/
│   └── features/
├── index.php
└── logout.php
```

## ⚙️ Installation

### Requirements
- PHP 7.4 or newer
- MySQL or MariaDB
- Apache (XAMPP is suitable)
- A web browser

### 1. Clone the repository

```bash
git clone https://github.com/tanvir-50198/online-auction-system.git
cd online-auction-system
```

> Replace the clone URL if your GitHub repository uses a different URL or name.

### 2. Move the project into the web root

For XAMPP on Windows, place the project folder inside:

```text
C:\xampp\htdocs\
```

### 3. Start services

Open the XAMPP Control Panel and start **Apache** and **MySQL**.

### 4. Create and import the database

1. Open `http://localhost/phpmyadmin`.
2. Create a database for the auction application.
3. Import `database/schema.sql`.

### 5. Configure the database

Open `config/database.php` and set the host, database name, username, and password to match your local MySQL configuration.

### 6. Check upload folders

Make sure `uploads/profiles/` and `uploads/listings/` exist and are writable by the local web server.

### 7. Run the application

Open the local project URL in your browser, for example:

```text
http://localhost/auction-system/
```

## 🔄 AJAX Functionality

AJAX endpoints are used for selected interactions, including:

- Dynamic buyer auction search
- Seller bid updates
- Admin category management

## 🔐 Security Notes

The project uses session-based authentication and role-oriented application flows. Before production deployment, review authorization checks, input validation, SQL query handling, password storage, and file-upload validation. Use prepared statements for database queries and never commit production credentials to GitHub.

## 🔮 Possible Improvements

- Email notifications
- Payment gateway integration
- WebSocket-based live bidding
- Password recovery
- Automated tests
- Improved reporting and analytics
- Accessibility and mobile usability improvements

## 👨‍💻 Author

**Tanvir Rahman**  
Computer Science & Engineering Student

- GitHub: [tanvir-50198](https://github.com/tanvir-50198)

## 📄 License

This project is intended for educational and portfolio purposes. Add a `LICENSE` file if you want to specify formal reuse terms.

## ⭐ Support

If you find this project useful, consider giving the repository a star.

**Thanks for visiting!**
