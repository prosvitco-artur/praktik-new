# Інструкція з налаштування сервера на Debian

Ця документація допоможе вам налаштувати старий ноутбук з Debian як сервер для WordPress проекту.

## Передумови

- Debian встановлено на ноутбук
- Доступ до інтернету
- Права root або sudo
- Статична IP адреса (рекомендовано) або налаштування динамічного DNS

**Важливо:** Lando та Docker НЕ потрібні на продакшн сервері! Lando використовується тільки для локальної розробки. На сервері все встановлюється нативно (без контейнерів).

## Крок 1: Налаштування Git аутентифікації

GitHub більше не підтримує аутентифікацію паролем. Вам потрібно налаштувати аутентифікацію через Personal Access Token або SSH ключ.

### Варіант 1: Personal Access Token (рекомендовано для початку)

```bash
cd deploy
chmod +x setup-git-auth.sh
./setup-git-auth.sh
```

Скрипт проведе вас через процес створення токену на GitHub.

### Варіант 2: SSH ключ (більш безпечно)

```bash
cd deploy
chmod +x setup-git-auth.sh
./setup-git-auth.sh
```

Виберіть варіант 2, і скрипт допоможе створити SSH ключ та додати його на GitHub.

### Ручне налаштування Personal Access Token

Якщо ви хочете налаштувати токен вручну:

1. Перейдіть на https://github.com/settings/tokens
2. Натисніть "Generate new token" → "Generate new token (classic)"
3. Вкажіть назву токену (наприклад: "praktik-server")
4. Виберіть права доступу: `repo` (повний доступ до репозиторіїв)
5. Натисніть "Generate token"
6. Скопіюйте токен (він показується тільки один раз!)

Після отримання токену:

```bash
git config --global credential.helper store
echo "https://prosvitco-artur:ВАШ_ТОКЕН@github.com" > ~/.git-credentials
chmod 600 ~/.git-credentials
```

Тепер ви можете клонувати репозиторій:

```bash
git clone https://github.com/prosvitco-artur/praktik-new.git
```

## Крок 2: Встановлення серверного програмного забезпечення

Запустіть скрипт автоматичного встановлення:

```bash
cd deploy
chmod +x install-server.sh
sudo ./install-server.sh
```

Скрипт встановить:
- Nginx (веб-сервер)
- MySQL (база даних)
- PHP 8.2 з необхідними розширеннями
- Composer (менеджер PHP залежностей)
- Node.js 18.x та Yarn (для збірки фронтенду)

Під час виконання скрипт попросить вас:
- Встановити пароль для root користувача MySQL
- Створити базу даних та користувача для WordPress
- Вказати директорію для проекту (за замовчуванням: `/var/www/praktik`)

**Важливо:** Збережіть інформацію про базу даних - вона знадобиться для налаштування `wp-config.php`!

## Крок 3: Деплой проекту

Після встановлення серверного ПЗ та налаштування Git аутентифікації, розгорніть проект:

```bash
cd deploy
chmod +x deploy.sh
sudo ./deploy.sh [домен]
```

Якщо у вас немає домену, використовуйте `localhost`:

```bash
sudo ./deploy.sh localhost
```

Скрипт:
- Клонує репозиторій з GitHub
- Встановлює залежності теми (Composer та Yarn)
- Збирає фронтенд ассети
- Налаштовує Nginx
- Створює необхідні директорії

## Крок 4: Налаштування wp-config.php

Після деплою потрібно налаштувати `wp-config.php`:

```bash
sudo nano /var/www/praktik/wp-config.php
```

Оновіть наступні рядки з даними, які ви вказали під час встановлення:

```php
define( 'DB_NAME', 'wordpress' );
define( 'DB_USER', 'wordpress' );
define( 'DB_PASSWORD', 'ваш_пароль' );
define( 'DB_HOST', 'localhost' );
```

Також переконайтеся, що `WP_DEBUG` встановлено в `false` для продакшену:

```php
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG_DISPLAY', false );
```

## Крок 5: Налаштування домену (опціонально)

Якщо у вас є домен, налаштуйте DNS записи:
- A запис: вказує на IP адресу вашого сервера
- Або CNAME запис (якщо використовуєте динамічний DNS)

Потім запустіть деплой з доменом:

```bash
sudo ./deploy.sh ваш-домен.com
```

## Крок 6: Налаштування SSL (рекомендовано)

Для безпеки налаштуйте SSL сертифікат через Let's Encrypt:

```bash
sudo apt-get install certbot python3-certbot-nginx
sudo certbot --nginx -d ваш-домен.com
```

Certbot автоматично налаштує Nginx для використання HTTPS.

## Крок 7: Завершення установки WordPress

1. Відкрийте сайт у браузері (за доменом або IP адресою)
2. Завершіть установку WordPress через веб-інтерфейс
3. Створіть адміністративний акаунт

## Оновлення проекту

Для оновлення проекту після змін у репозиторії:

```bash
cd /var/www/praktik
sudo git pull origin main
cd wp-content/themes/praktik
sudo -u www-data yarn install
sudo -u www-data yarn build
sudo systemctl reload nginx
```

## Структура файлів

```
deploy/
├── README.md              # Ця інструкція
├── install-server.sh      # Скрипт встановлення серверного ПЗ
├── deploy.sh              # Скрипт деплою проекту
├── setup-git-auth.sh      # Скрипт налаштування Git аутентифікації
└── nginx-config.conf      # Шаблон конфігурації Nginx
```

## Налаштування firewall

Якщо використовуєте UFW:

```bash
sudo ufw allow 'Nginx Full'
sudo ufw allow OpenSSH
sudo ufw enable
```

## Моніторинг та логування

Переглянути логи Nginx:
```bash
sudo tail -f /var/log/nginx/error.log
sudo tail -f /var/log/nginx/access.log
```

Переглянути логи PHP:
```bash
sudo tail -f /var/log/php8.2-fpm.log
```

## Резервне копіювання

Рекомендується налаштувати автоматичне резервне копіювання:

1. База даних (через cron):
```bash
sudo crontab -e
# Додайте рядок для щоденного бек-апу БД
0 2 * * * mysqldump -u wordpress -p'пароль' wordpress > /backup/db-$(date +\%Y\%m\%d).sql
```

2. Файли проекту (через rsync або tar)

## Усунення проблем

### Помилка при клонуванні репозиторію
- Перевірте налаштування Git аутентифікації (крок 1)
- Переконайтеся, що токен або SSH ключ додано правильно

### Помилка доступу до бази даних
- Перевірте налаштування в `wp-config.php`
- Переконайтеся, що MySQL запущено: `sudo systemctl status mysql`

### Сайт не відкривається
- Перевірте статус Nginx: `sudo systemctl status nginx`
- Перевірте конфігурацію: `sudo nginx -t`
- Перевірте логи: `sudo tail -f /var/log/nginx/error.log`

### Помилки прав доступу
- Переконайтеся, що файли належать `www-data`: `sudo chown -R www-data:www-data /var/www/praktik`

## Додаткові ресурси

- [Документація WordPress](https://wordpress.org/support/)
- [Документація Nginx](https://nginx.org/en/docs/)
- [Документація MySQL](https://dev.mysql.com/doc/)

