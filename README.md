# LPA Travel Shop

A small online shop for travel gear (backpacks, tents and jackets), built with **PHP** and **MySQL** as a project for my Diploma of Information Technology at CTI Brisbane (2026).

> This is a study project. I built it with a lot of help from AI tools. It helped me understand how a website, a web server and a database work together.

## Features

- User sign-up and login (passwords are hashed)
- Product pages by category, with live stock
- Shopping cart and checkout (example payment form – no real payment)
- Order history, order details and order cancellation
- Admin area: dashboard, add / edit / delete products, stock management

## Built with

PHP · MySQL / MariaDB · HTML · CSS · JavaScript · XAMPP (Apache + MySQL)

## Run it locally

1. Install [XAMPP](https://www.apachefriends.org) and start **Apache** and **MySQL** from the XAMPP Control Panel.
2. Download or clone this repository, rename the folder to **`lpa_travel_shop`** and place it in `C:\xampp\htdocs\`.
3. Open http://localhost/phpmyadmin and create a database named **`lpa_travelshop_db`**.
4. Select that database, click **Import**, choose `database/lpa_travelshop_db.sql` and click **Go**.
5. Open http://localhost/lpa_travel_shop in your browser.

The database settings are in `config.php` (default XAMPP values: user `root`, no password).
If you put the folder somewhere else, set the `APP_BASE_PATH` environment variable (default: `/lpa_travel_shop/`).

## Demo accounts

| Role | Email | Password |
| --- | --- | --- |
| Admin | admin@travelshop.com | admin123 |
| Customer | customer@example.com | customer123 |

These are demo accounts for local testing only.

## How it works

1. The browser requests a page from the web server (Apache).
2. PHP reads or updates the MySQL database (products, users, orders, stock).
3. When a user logs in, PHP checks the password hash and starts a session.
4. Adding to the cart reserves the stock; cancelling or removing an item puts it back.

Database queries use prepared statements, and passwords are stored as hashes.

## Limitations

- Study project, made to run locally – not built for real production use.
- The payment form is an example only: no payment is processed and card details are not stored.
- The admin account is identified by its email address.
- Sales figures on the admin dashboard are fictional.

## Project structure

```
lpa_travel_shop/
├── admin/        Admin pages (dashboard, add / edit / delete products)
├── css/          Styles
├── database/     SQL file to create the database
├── images/       Site and product images
├── includes/     Shared header, footer and the add-to-cart endpoint
├── js/           Front-end script
├── products/     Product category pages
└── *.php         Public pages (home, login, cart, checkout, orders…)
```
