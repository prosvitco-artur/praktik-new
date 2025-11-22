#!/bin/bash

set -e

echo "=========================================="
echo "Налаштування Lando на сервері"
echo "=========================================="

if [ "$EUID" -ne 0 ]; then 
    echo "Будь ласка, запустіть скрипт з правами root (sudo)"
    exit 1
fi

export DEBIAN_FRONTEND=noninteractive

echo ""
echo "1. Перевірка встановлення Docker..."
if ! command -v docker &> /dev/null; then
    echo "Docker не встановлено. Встановлюю Docker..."
    
    apt-get update
    apt-get install -y \
        apt-transport-https \
        ca-certificates \
        curl \
        gnupg \
        lsb-release
    
    curl -fsSL https://download.docker.com/linux/debian/gpg | gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg
    
    echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/debian $(lsb_release -cs) stable" | tee /etc/apt/sources.list.d/docker.list > /dev/null
    
    apt-get update
    apt-get install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin
    
    systemctl enable docker
    systemctl start docker
    echo "Docker встановлено та запущено."
else
    echo "Docker вже встановлено."
fi

echo ""
echo "2. Перевірка встановлення Lando..."
if ! command -v lando &> /dev/null; then
    echo "Lando не встановлено. Встановлюю Lando..."
    
    wget -O- https://get.lando.dev/install.sh | bash
    
    echo "Lando встановлено."
else
    echo "Lando вже встановлено."
    lando version
fi

echo ""
echo "3. Налаштування прав доступу до Docker..."
CURRENT_USER=${SUDO_USER:-$USER}

if [ -z "$CURRENT_USER" ] || [ "$CURRENT_USER" = "root" ]; then
    echo "Не вдалося визначити користувача. Будь ласка, вкажіть ім'я користувача вручну:"
    read CURRENT_USER
fi

if id "$CURRENT_USER" &>/dev/null; then
    echo "Додаю користувача $CURRENT_USER до групи docker..."
    usermod -aG docker "$CURRENT_USER"
    
    echo "Налаштування прав на Docker socket..."
    chmod 666 /var/run/docker.sock 2>/dev/null || true
    
    echo "Перезапускаю Docker daemon..."
    systemctl restart docker
    
    echo ""
    echo "=========================================="
    echo "Налаштування завершено!"
    echo "=========================================="
    echo ""
    echo "ВАЖЛИВО: Для застосування змін потрібно:"
    echo "  1. Вийти з системи та увійти знову, АБО"
    echo "  2. Виконати команду: newgrp docker"
    echo ""
    echo "Після цього ви зможете використовувати Lando без sudo:"
    echo "  cd /шлях/до/проекту"
    echo "  lando start"
    echo ""
else
    echo "Помилка: Користувач $CURRENT_USER не існує."
    exit 1
fi

