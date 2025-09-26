# Multipublications Backend

This repository contains the Laravel-based backend for the Multipublications platform. It exposes a REST API that allows you to manage fashion products, attach images, and track multi-marketplace listings (Wallapop, Depop, eBay, Shopify, and Vinted).

## Requirements

- PHP 8.2+
- Composer
- Database server (MySQL, PostgreSQL, or SQLite)

## Getting Started

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

The API is namespaced under `/api/v1` and the primary resource is `products`.

## Available Endpoints

- `GET /api/v1/products` – List products with filtering options for brand, gender, condition, size, price range, marketplace platform, sold status, and creation date range.
- `POST /api/v1/products` – Create a product with images and marketplace listings.
- `GET /api/v1/products/{id}` – Retrieve a single product.
- `PUT /api/v1/products/{id}` – Update a product, replacing images/listings when provided.
- `DELETE /api/v1/products/{id}` – Delete a product and cascade related data.

Refer to `tests/Feature/ProductApiTest.php` for request/response examples.
