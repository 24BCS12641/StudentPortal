FROM php:8.2-apache

# Install MariaDB server, client, system tools, and PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    mariadb-server \
    mariadb-client \
    libssl-dev \
    pkg-config \
    unzip \
    && docker-php-ext-install -j$(nproc) mysqli pdo pdo_mysql \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && a2enmod rewrite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Configure permissions for upload folders and MariaDB
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && mkdir -p /var/www/html/uploads/assignments \
    && mkdir -p /var/www/html/uploads/submissions \
    && mkdir -p /var/www/html/uploads/study_materials \
    && mkdir -p /var/www/html/uploads/profile_pictures \
    && chmod -R 777 /var/www/html/uploads

# Copy and set entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80 10000

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
