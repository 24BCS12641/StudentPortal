#!/bin/bash
set -e

# Render sets the PORT environment variable (default: 80)
PORT="${PORT:-80}"

# Adjust Apache configuration dynamically to listen on $PORT
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf 2>/dev/null || sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf

# If no external cloud DB_HOST is set, start the built-in MariaDB server
if [ -z "$DB_HOST" ] || [ "$DB_HOST" = "localhost" ] || [ "$DB_HOST" = "127.0.0.1" ]; then
    echo "Starting internal MariaDB service..."
    service mariadb start || /etc/init.d/mariadb start

    # Wait for MariaDB to become ready
    for i in {1..30}; do
        if mysqladmin ping --silent 2>/dev/null; then
            echo "MariaDB is ready!"
            break
        fi
        sleep 1
    done

    # Allow passwordless root connection from localhost for www-data (Apache)
    mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED VIA mysql_native_password USING PASSWORD(''); FLUSH PRIVILEGES;" 2>/dev/null || \
    mysql -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' IDENTIFIED BY '' WITH GRANT OPTION; FLUSH PRIVILEGES;" 2>/dev/null || true

    # Check and import student_portal database if not present
    if ! mysql -e "USE student_portal; SELECT 1 FROM users LIMIT 1;" 2>/dev/null; then
        echo "Creating and populating student_portal database..."
        mysql -e "CREATE DATABASE IF NOT EXISTS student_portal DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
        mysql student_portal < /var/www/html/database/student_portal.sql 2>/dev/null || true
        mysql student_portal < /var/www/html/database/sample_data.sql 2>/dev/null || true
        echo "student_portal database successfully initialized!"
    else
        echo "student_portal database is already initialized."
    fi
else
    echo "External database host detected: ${DB_HOST}. Skipping internal MariaDB."
fi

echo "EduPortal: Apache listening on port ${PORT}..."
exec "$@"
