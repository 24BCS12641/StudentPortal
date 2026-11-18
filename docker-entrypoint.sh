#!/bin/bash
set -e

# Render sets the PORT environment variable (default: 80)
PORT="${PORT:-80}"

# Adjust Apache configuration dynamically to listen on $PORT
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf 2>/dev/null || sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf

# If no external cloud DB_HOST is set, start the built-in MariaDB server
if [ -z "$DB_HOST" ] || [ "$DB_HOST" = "localhost" ] || [ "$DB_HOST" = "127.0.0.1" ]; then
    echo "Setting up MariaDB runtime directories..."
    mkdir -p /run/mysqld /var/run/mysqld /var/lib/mysql /var/log/mysql /tmp
    chown -R mysql:mysql /run/mysqld /var/run/mysqld /var/lib/mysql /var/log/mysql
    chmod 777 /run/mysqld /var/run/mysqld /tmp

    if [ ! -d "/var/lib/mysql/mysql" ]; then
        echo "Initializing MariaDB system tables..."
        mariadb-install-db --user=mysql --datadir=/var/lib/mysql >/dev/null 2>&1 || mysql_install_db --user=mysql --datadir=/var/lib/mysql >/dev/null 2>&1 || true
    fi

    echo "Starting MariaDB server daemon on 127.0.0.1:3306..."
    if [ -x "/usr/sbin/mariadbd" ]; then
        /usr/sbin/mariadbd --user=mysql --skip-grant-tables --skip-name-resolve --bind-address=0.0.0.0 &
    elif [ -x "/usr/sbin/mysqld" ]; then
        /usr/sbin/mysqld --user=mysql --skip-grant-tables --skip-name-resolve --bind-address=0.0.0.0 &
    else
        service mariadb start || /etc/init.d/mariadb start || true
    fi

    # Wait up to 30 seconds for MariaDB TCP port 3306
    echo "Waiting for MariaDB port 3306 to respond..."
    for i in {1..30}; do
        if mysqladmin --protocol=tcp -h 127.0.0.1 -u root ping --silent 2>/dev/null; then
            echo "MariaDB is successfully responding on 127.0.0.1:3306!"
            break
        fi
        sleep 1
    done

    # Create and import database tables
    echo "Verifying student_portal database in MariaDB..."
    mysql --protocol=tcp -h 127.0.0.1 -u root -e "CREATE DATABASE IF NOT EXISTS student_portal DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null || true
    if [ -f "/var/www/html/database/college_website.sql" ]; then
        echo "Importing full university schema & tables..."
        mysql --protocol=tcp -h 127.0.0.1 -u root student_portal < /var/www/html/database/college_website.sql 2>/dev/null || true
    fi
    mysql --protocol=tcp -h 127.0.0.1 -u root student_portal < /var/www/html/database/student_portal.sql 2>/dev/null || true
    mysql --protocol=tcp -h 127.0.0.1 -u root student_portal < /var/www/html/database/sample_data.sql 2>/dev/null || true
    
    # Ensure modern columns exist across environments
    mysql --protocol=tcp -h 127.0.0.1 -u root student_portal -e "
        ALTER TABLE students ADD COLUMN IF NOT EXISTS department_id INT DEFAULT 1;
        ALTER TABLE students ADD COLUMN IF NOT EXISTS course_id INT DEFAULT 1;
        ALTER TABLE attendance ADD COLUMN IF NOT EXISTS marked_by INT DEFAULT NULL;
        ALTER TABLE attendance ADD COLUMN IF NOT EXISTS remarks VARCHAR(255) DEFAULT NULL;
    " 2>/dev/null || true
    echo "Database initialization and schema upgrade finished successfully!"
else
    echo "External cloud database host detected: ${DB_HOST}. Skipping internal MariaDB."
fi

echo "EduPortal: Starting Apache on port ${PORT}..."
exec "$@"
