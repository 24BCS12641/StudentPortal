#!/bin/bash
set -e

# Render sets the PORT environment variable (default: 80)
PORT="${PORT:-80}"

# Adjust Apache configuration dynamically to listen on $PORT
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf 2>/dev/null || sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf

echo "EduPortal: Apache listening on port ${PORT}..."
exec "$@"
