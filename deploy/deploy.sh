#!/bin/bash

set -e

echo "=========================================="
echo "Деплой WordPress проекту на сервер"
echo "=========================================="

if [ "$EUID" -ne 0 ]; then 
    echo "Будь ласка, запустіть скрипт з правами root (sudo)"
    exit 1
fi

PROJECT_DIR=${PROJECT_DIR:-/var/www/praktik}
DOMAIN=${1:-localhost}
GIT_REPO=${GIT_REPO:-https://github.com/prosvitco-artur/praktik-new.git}

echo ""
echo "1. Перевірка директорії проекту..."
if [ ! -d "$PROJECT_DIR" ]; then
    echo "Директорія $PROJECT_DIR не існує. Створюю..."
    mkdir -p $PROJECT_DIR
fi

echo ""
echo "2. Клонування або оновлення репозиторію..."
if [ -d "$PROJECT_DIR/.git" ]; then
    echo "Репозиторій вже існує. Оновлюю..."
    cd $PROJECT_DIR
    git pull origin main || git pull origin master
else
    echo "Клоную репозиторій з GitHub..."
    cd $(dirname $PROJECT_DIR)
    git clone $GIT_REPO $(basename $PROJECT_DIR)
    cd $PROJECT_DIR
fi

echo ""
echo "3. Встановлення прав доступу..."
chown -R www-data:www-data $PROJECT_DIR
find $PROJECT_DIR -type d -exec chmod 755 {} \;
find $PROJECT_DIR -type f -exec chmod 644 {} \;

echo ""
echo "4. Перевірка wp-config.php..."
if [ ! -f "$PROJECT_DIR/wp-config.php" ]; then
    echo "Увага: wp-config.php не знайдено!"
    echo "Будь ласка, створіть wp-config.php вручну або скопіюйте з wp-config-sample.php"
    echo "Ви можете використати інформацію з install-server.sh для налаштування БД"
fi

echo ""
echo "5. Встановлення залежностей теми..."
if [ -f "$PROJECT_DIR/wp-content/themes/praktik/composer.json" ]; then
    echo "Встановлення PHP залежностей..."
    cd $PROJECT_DIR/wp-content/themes/praktik
    sudo -u www-data composer install --no-dev --optimize-autoloader || {
        echo "Помилка встановлення Composer залежностей. Перевірте наявність composer."
    }
fi

if [ -f "$PROJECT_DIR/wp-content/themes/praktik/package.json" ]; then
    echo "Встановлення Node.js залежностей..."
    cd $PROJECT_DIR/wp-content/themes/praktik
    if command -v yarn &> /dev/null; then
        sudo -u www-data yarn install --production || {
            echo "Помилка встановлення Yarn залежностей."
        }
        echo "Збірка ассетів..."
        sudo -u www-data yarn build || {
            echo "Помилка збірки ассетів. Перевірте налаштування."
        }
    else
        echo "Yarn не встановлено. Пропускаю встановлення Node.js залежностей."
    fi
fi

echo ""
echo "6. Створення директорій для завантажень та кешу..."
mkdir -p $PROJECT_DIR/wp-content/uploads
mkdir -p $PROJECT_DIR/wp-content/cache
chown -R www-data:www-data $PROJECT_DIR/wp-content/uploads
chown -R www-data:www-data $PROJECT_DIR/wp-content/cache
chmod -R 755 $PROJECT_DIR/wp-content/uploads
chmod -R 755 $PROJECT_DIR/wp-content/cache

echo ""
echo "7. Налаштування Nginx..."
NGINX_CONFIG_DIR="$(dirname $0)"
if [ -f "$NGINX_CONFIG_DIR/nginx-config.conf" ]; then
    if [ "$DOMAIN" != "localhost" ]; then
        sed "s|server_name _;|server_name $DOMAIN;|" \
            "$NGINX_CONFIG_DIR/nginx-config.conf" > /etc/nginx/sites-available/praktik
        sed -i "s|root /var/www/praktik;|root $PROJECT_DIR;|" /etc/nginx/sites-available/praktik
    else
        sed "s|root /var/www/praktik;|root $PROJECT_DIR;|" \
            "$NGINX_CONFIG_DIR/nginx-config.conf" > /etc/nginx/sites-available/praktik
    fi
    
    if [ -f /etc/nginx/sites-enabled/default ]; then
        rm /etc/nginx/sites-enabled/default
    fi
    
    if [ ! -L /etc/nginx/sites-enabled/praktik ]; then
        ln -s /etc/nginx/sites-available/praktik /etc/nginx/sites-enabled/praktik
    fi
    
    nginx -t
    systemctl reload nginx
else
    echo "Файл конфігурації Nginx не знайдено. Пропускаю налаштування."
fi

echo ""
echo "8. Налаштування WordPress cron..."
(crontab -l 2>/dev/null | grep -v "wp-cron.php"; echo "*/15 * * * * cd $PROJECT_DIR && php wp-cron.php > /dev/null 2>&1") | crontab -

echo ""
echo "=========================================="
echo "Деплой завершено!"
echo "=========================================="
echo ""
echo "Проект розгорнуто в: $PROJECT_DIR"
echo "Домен: $DOMAIN"
echo ""
echo "Наступні кроки:"
echo "  1. Перевірте налаштування wp-config.php"
echo "  2. Відкрийте сайт у браузері та завершіть установку WordPress"
if [ "$DOMAIN" != "localhost" ]; then
    echo "  3. Налаштуйте SSL сертифікат (certbot)"
fi
echo ""
