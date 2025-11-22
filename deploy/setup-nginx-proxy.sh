#!/bin/bash

set -e

echo "=========================================="
echo "Налаштування Nginx як reverse proxy для Lando"
echo "=========================================="

if [ "$EUID" -ne 0 ]; then 
    echo "Будь ласка, запустіть скрипт з правами root (sudo)"
    exit 1
fi

export DEBIAN_FRONTEND=noninteractive

echo ""
echo "1. Встановлення Nginx..."
if ! command -v nginx &> /dev/null; then
    apt-get update
    apt-get install -y nginx
    systemctl enable nginx
    systemctl start nginx
    echo "Nginx встановлено."
else
    echo "Nginx вже встановлено."
fi

echo ""
echo "2. Налаштування конфігурації..."
echo "Введіть домен або IP адресу для доступу до сайту (наприклад: praktik.example.com або 192.168.1.100):"
read SERVER_NAME

if [ -z "$SERVER_NAME" ]; then
    echo "Помилка: Домен або IP не вказано."
    exit 1
fi

CONFIG_FILE="/etc/nginx/sites-available/lando-proxy"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

cat > $CONFIG_FILE <<EOF
server {
    listen 80;
    listen [::]:80;
    server_name $SERVER_NAME;

    location / {
        proxy_pass https://praktik-new.lndo.site;
        proxy_set_header Host praktik-new.lndo.site;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto \$scheme;
        proxy_set_header X-Forwarded-Host \$host;
        proxy_ssl_verify off;
        
        proxy_http_version 1.1;
        proxy_set_header Upgrade \$http_upgrade;
        proxy_set_header Connection "upgrade";
    }
}
EOF

if [ -f /etc/nginx/sites-enabled/default ]; then
    rm /etc/nginx/sites-enabled/default
fi

if [ ! -L /etc/nginx/sites-enabled/lando-proxy ]; then
    ln -s $CONFIG_FILE /etc/nginx/sites-enabled/lando-proxy
fi

echo ""
echo "3. Перевірка конфігурації Nginx..."
nginx -t

if [ $? -eq 0 ]; then
    echo "Конфігурація валідна. Перезавантажую Nginx..."
    systemctl reload nginx
    
    echo ""
    echo "=========================================="
    echo "Налаштування завершено!"
    echo "=========================================="
    echo ""
    echo "Сайт доступний за адресою: http://$SERVER_NAME"
    echo ""
    echo "ВАЖЛИВО: Переконайтеся, що:"
    echo "  1. Lando запущено: lando start"
    echo "  2. Firewall дозволяє вхідні з'єднання на порт 80"
    echo "  3. Якщо є домен, налаштуйте DNS записи"
    echo ""
else
    echo "Помилка в конфігурації Nginx!"
    exit 1
fi

