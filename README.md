# Headless WordPress Docker Environment...

## Description
This project provides a Docker-based environment for running WordPress, suitable for headless setups. It includes Redis for caching and is easily configurable via environment variables.

---

## Features
- WordPress (latest) running in Docker
- Redis caching support
- Customizable via `.env` file
- Persistent `wp-content` volume for themes/plugins
- Pre-configured `.htaccess` for authorization headers (useful for JWT and REST APIs)

---

## Prerequisites
- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/)

---

## Setup

1. **Clone the repository:**
   ```sh
   git clone <your-repo-url>
   cd wp-headless
   ```

2. **Copy and configure environment variables:**
   ```sh
   cp .env-exam .env
   ```
   Edit `.env` and fill in your database, Redis, and JWT settings.

3. **Start the services:**
   ```sh
   docker-compose up --build
   ```
   - WordPress will be available at [http://localhost:8888](http://localhost:8888)
   - Redis runs on port 6379

---

## Environment Variables

- `WORDPRESS_DB_HOST`
- `WORDPRESS_DB_NAME`
- `WORDPRESS_DB_USER`
- `WORDPRESS_DB_PASSWORD`
- `WORDPRESS_TABLE_PREFIX`
- `WP_DEBUG`
- `REDIS_PASSWORD`
- `WP_ENVIRONMENT_TYPE`
- `JWT_AUTH_SECRET_KEY`
- `JWT_AUTH_CORS_ENABLE`

See `.env-exam` for all options.

---

## Volumes

- `./wp-content:/var/www/html/wp-content` (WordPress content)
- `redis_data:/data` (Redis persistence)

---

## Customization

- Place your custom themes/plugins in `wp-content/`.
- The `.htaccess` is pre-configured for REST/JWT support.

---

## Stopping and Removing

To stop:
```sh
docker-compose down
```
To remove all data (including Redis cache):
```sh
docker-compose down -v
```

---

## Required Plugins (Please install manually)

The following plugins are required for this setup:

1. [Redis Object Cache](https://wordpress.org/plugins/redis-cache/)
   - Provides persistent object caching using Redis
   - Improves WordPress performance
   - Configured automatically via environment variables

2. [JWT Authentication for WP REST API](https://wordpress.org/plugins/jwt-authentication-for-wp-rest-api/)
   - Enables JWT authentication for the WordPress REST API
   - Required for secure API authentication
   - Configured via environment variables:
     - `JWT_AUTH_SECRET_KEY`
     - `JWT_AUTH_CORS_ENABLE`

Install these plugins through the WordPress admin panel or place them in the `wp-content/plugins` directory.

---

## Troubleshooting

- Ensure all environment variables are set in `.env`.
- Check Docker logs for errors:
  ```sh
  docker-compose logs
  ```
