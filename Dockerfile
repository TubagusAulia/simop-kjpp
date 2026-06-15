# ============================================
# SIMOP KJPP — Generic Laravel Docker Image
# Works with Docker and Podman (podman build)
# ============================================

FROM php:8.3-fpm-alpine AS base

# --------------------------------------------
# Install system dependencies
# --------------------------------------------
RUN apk add --no-cache \
    nginx \
    curl \
    zip \
    unzip \
    libxml2-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    linux-headers \
    && docker-php-ext-install \
    pdo \
    pdo_sqlite \
    mbstring \
    xml \
    curl \
    zip \
    bcmath \
    fileinfo \
    intl \
    dom

# --------------------------------------------
# Install Composer
# --------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# --------------------------------------------
# Configure Nginx
# --------------------------------------------
RUN mkdir -p /run/nginx

COPY <<'EOF' /etc/nginx/http.d/default.conf
server {
    listen 80;
    server_name _;
    root /var/www/html/public;
    index index.php index.html;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# --------------------------------------------
# Copy application code
# --------------------------------------------
WORKDIR /var/www/html

COPY . /var/www/html

# --------------------------------------------
# Install PHP dependencies
# --------------------------------------------
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# --------------------------------------------
# Set permissions
# --------------------------------------------
RUN mkdir -p /var/www/html/database \
    && touch /var/www/html/database/database.sqlite \
    && chown -R www-data:www-data /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/database \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/database

# --------------------------------------------
# Generate .env if not exists
# --------------------------------------------
RUN cp .env.example .env \
    && php artisan key:generate

# --------------------------------------------
# Startup script
# --------------------------------------------
COPY <<'EOF' /usr/local/bin/start.sh
#!/bin/sh
set -e

# Run migrations
php artisan migrate --force --no-interaction

# Cache config for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP-FPM and Nginx
php-fpm -D
nginx -g 'daemon off;'
EOF

RUN chmod +x /usr/local/bin/start.sh

# --------------------------------------------
# Health check
# --------------------------------------------
HEALTHCHECK --interval=30s --timeout=3s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]
