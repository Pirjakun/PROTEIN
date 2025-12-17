# Buitenworks Protein Project

Protein is a robust e-commerce and product catalog web application built with [Laravel](https://laravel.com). It serves as a digital storefront for Buitenworks, featuring a dynamic product catalog, comprehensive admin dashboard, and content management for community and archives.

## 🚀 Features

### User & Public Facing
-   **User Authentication**: Secure login and registration.
-   **Dynamic Product Catalog**: Browse products with filtering by category.
-   **Product Details**: Rich product views with image galleries and stock information.
-   **Shopping Cart**: (In Progress) Add items to cart.
-   **Content Pages**:
    -   **Community**: Grid view of community posts.
    -   **Archives**: Blog/Archive section with horizontal scrolling or grid layouts.
    -   **About Us**: Company information and social media links.
-   **Profile Management**: Update user details.

### Admin Dashboard
-   **Product Management**:
    -   Create, Edit, Delete products.
    -   **Gallery Management**: Upload multiple images, delete specific images with confirmation.
    -   **Featured Products**: Toggle "Featured" status (Limit: 3 products).
    -   **Stock Control**: Manage inventory levels.
-   **Category Management**: Full CRUD keys for product categories.
-   **Archive Management**: Manage archive posts (Title, Description, Images).

## 🛠 Tech Stack

-   **Framework**: Laravel 12.x
-   **Language**: PHP 8.2+
-   **Frontend**: Blade Templates, Bootstrap 5, Custom CSS
-   **Database**: MySQL
-   **Asset Build**: Vite

## ⚙️ Installation

Follow these steps to set up the project locally:

1.  **Clone the repository**:
    ```bash
    git clone <repository-url>
    cd protein
    ```

2.  **Install PHP dependencies**:
    ```bash
    composer install
    ```

3.  **Install Node.js dependencies and build assets**:
    ```bash
    npm install
    npm run build
    ```

4.  **Environment Configuration**:
    Copy the example environment file and configure your database settings.
    ```bash
    cp .env.example .env
    ```
    Update the `DB_` variables in `.env` to match your database configuration.

5.  **Generate Application Key**:
    ```bash
    php artisan key:generate
    ```

6.  **Database Setup (CRITICAL)**:
    Run migrations and seed the database to populate it with essential data (Products, Categories, Admin User).
    ```bash
    php artisan migrate:refresh --seed
    ```

## 🖥 Usage

Start the local development server:

```bash
php artisan serve
```

Access the application at `http://localhost:8000`.

### Admin Access
To access the admin dashboard, login with an account that has the `is_admin` flag set to `1` in the database.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
