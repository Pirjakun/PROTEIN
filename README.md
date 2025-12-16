# Protein Project

Protein is a web application built with [Laravel](https://laravel.com), designed to serve as an e-commerce platform or product catalog. It features user authentication, a dynamic product catalog, and a shopping cart system.

## Features

-   **User Authentication**: Secure login and registration functionality for users.
-   **Product Catalog**: Browse a dynamic list of products with detailed views.
-   **Shopping Cart**: Add items to a cart and view the checkout page.
-   **Informational Pages**: Access static pages like About Us, Community, and Archives.

## Tech Stack

-   **Framework**: Laravel 12.x
-   **Language**: PHP 8.2+
-   **Frontend**: Blade Templates, Vite
-   **Database**: MySQL / SQLite (configurable)

## Installation

Follow these steps to set up the project locally:

1.  **Clone the repository** (if you haven't already):
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

6.  **Run Database Migrations**:
    ```bash
    php artisan migrate
    ```

## Usage

Start the local development server:

```bash
php artisan serve
```

Access the application in your browser at `http://localhost:8000`.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
