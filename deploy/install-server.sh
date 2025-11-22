#!/bin/bash

set -e

echo "=========================================="
echo "Налаштування сервера для WordPress проекту"
echo "=========================================="

if [ "$EUID" -ne 0 ]; then 
    echo "Будь ласка, запустіть скрипт з правами root (sudo)"
    exit 1
fi

export DEBIAN_FRONTEND=noninteractive

echo ""
echo "1. Оновлення системи..."
apt-get update
apt-get upgrade -y

echo ""
echo "2. Встановлення базових пакетів..."
apt-get install -y \
    curl \
    wget \
    git \
    unzip \
    software-properties-common \
    apt-transport-https \
    ca-certificates \
    gnupg \
    lsb-release

echo ""
echo "3. Встановлення Nginx..."
apt-get install -y nginx
systemctl enable nginx
systemctl start nginx

echo ""
echo "4. Встановлення MySQL..."
apt-get install -y mysql-server
systemctl enable mysql
systemctl start mysql

echo ""
echo "5. Встановлення PHP 8.2 та необхідних розширень..."
apt-get install -y \
    php8.2 \
    php8.2-fpm \
    php8.2-mysql \
    php8.2-curl \
    php8.2-xml \
    php8.2-mbstring \
    php8.2-zip \
    php8.2-gd \
    php8.2-intl \
    php8.2-bcmath \
    php8.2-opcache \
    php8.2-readline

systemctl enable php8.2-fpm
systemctl start php8.2-fpm

echo ""
echo "6. Налаштування PHP..."
PHP_INI="/etc/php/8.2/fpm/php.ini"
sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 64M/' $PHP_INI
sed -i 's/post_max_size = 8M/post_max_size = 64M/' $PHP_INI
sed -i 's/memory_limit = 128M/memory_limit = 256M/' $PHP_INI
sed -i 's/max_execution_time = 30/max_execution_time = 300/' $PHP_INI
sed -i 's/max_input_time = 60/max_input_time = 300/' $PHP_INI

systemctl restart php8.2-fpm

echo ""
echo "7. Встановлення Composer..."
cd /tmp
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

echo ""
echo "8. Встановлення Node.js 18.x через NodeSource..."
curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
apt-get install -y nodejs

echo ""
echo "9. Встановлення Yarn..."
npm install -g yarn

echo ""
echo "10. Налаштування MySQL безпеки..."
echo "Встановіть пароль для root користувача MySQL:"
read -s MYSQL_ROOT_PASSWORD
mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '${MYSQL_ROOT_PASSWORD}';"
mysql -e "DELETE FROM mysql.user WHERE User='';"
mysql -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');"
mysql -e "DROP DATABASE IF EXISTS test;"
mysql -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';"
mysql -e "FLUSH PRIVILEGES;"

echo ""
echo "11. Створення користувача та бази даних для WordPress..."
echo "Введіть назву бази даних (за замовчуванням: wordpress):"
read DB_NAME
DB_NAME=${DB_NAME:-wordpress}

echo "Введіть ім'я користувача БД (за замовчуванням: wordpress):"
read DB_USER
DB_USER=${DB_USER:-wordpress}

echo "Введіть пароль для користувача БД:"
read -s DB_PASSWORD

mysql -u root -p${MYSQL_ROOT_PASSWORD} <<EOF
CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';
GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
EOF

echo ""
echo "12. Створення директорії для проекту..."
echo "Введіть шлях до директорії проекту (за замовчуванням: /var/www/praktik):"
read PROJECT_DIR
PROJECT_DIR=${PROJECT_DIR:-/var/www/praktik}

mkdir -p $PROJECT_DIR
chown -R www-data:www-data $PROJECT_DIR
chmod -R 755 $PROJECT_DIR

echo ""
echo "13. Налаштування firewall..."
if command -v ufw &> /dev/null; then
    ufw allow 'Nginx Full'
    ufw allow OpenSSH
    echo "Firewall налаштовано. Увімкнути його зараз? (y/n)"
    read ENABLE_FIREWALL
    if [ "$ENABLE_FIREWALL" = "y" ]; then
        ufw --force enable
    fi
fi

echo ""
echo "=========================================="
echo "Встановлення завершено!"
echo "=========================================="
echo ""
echo "Інформація для налаштування:"
echo "  База даних: ${DB_NAME}"
echo "  Користувач БД: ${DB_USER}"
echo "  Пароль БД: ${DB_PASSWORD}"
echo "  Директорія проекту: ${PROJECT_DIR}"
echo ""
echo "Збережіть цю інформацію в безпечному місці!"
echo ""
echo "Наступні кроки:"
echo "  1. Скопіюйте файли проекту в ${PROJECT_DIR}"
echo "  2. Налаштуйте wp-config.php з вищевказаними даними БД"
echo "  3. Запустіть скрипт deploy.sh для деплою проекту"
echo ""

