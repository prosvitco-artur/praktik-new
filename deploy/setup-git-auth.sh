#!/bin/bash

set -e

echo "=========================================="
echo "Налаштування Git аутентифікації"
echo "=========================================="

echo ""
echo "GitHub більше не підтримує аутентифікацію паролем."
echo "Вам потрібно використати один з варіантів:"
echo ""
echo "1. Personal Access Token (PAT) - найпростіший спосіб"
echo "2. SSH ключ - більш безпечний спосіб"
echo ""
echo "Який варіант ви хочете використати? (1/2)"
read AUTH_METHOD

if [ "$AUTH_METHOD" = "1" ]; then
    echo ""
    echo "=== Налаштування через Personal Access Token ==="
    echo ""
    echo "Якщо у вас ще немає токену, створіть його:"
    echo "1. Перейдіть на https://github.com/settings/tokens"
    echo "2. Натисніть 'Generate new token' -> 'Generate new token (classic)'"
    echo "3. Вкажіть назву токену (наприклад: 'praktik-server')"
    echo "4. Виберіть права доступу:"
    echo "   - repo (повний доступ до репозиторіїв)"
    echo "5. Натисніть 'Generate token'"
    echo "6. Скопіюйте токен (він показується тільки один раз!)"
    echo ""
    echo "Введіть ваш Personal Access Token:"
    read -s GITHUB_TOKEN
    
    echo ""
    echo "Введіть ваш GitHub username:"
    read GITHUB_USERNAME
    
    echo ""
    echo "Налаштування Git для використання токену..."
    git config --global credential.helper store
    echo "https://${GITHUB_USERNAME}:${GITHUB_TOKEN}@github.com" > ~/.git-credentials
    chmod 600 ~/.git-credentials
    
    echo ""
    echo "Тепер ви можете клонувати репозиторій:"
    echo "git clone https://github.com/prosvitco-artur/praktik-new.git"
    
elif [ "$AUTH_METHOD" = "2" ]; then
    echo ""
    echo "=== Налаштування через SSH ключ ==="
    echo ""
    
    if [ ! -f ~/.ssh/id_rsa.pub ]; then
        echo "SSH ключ не знайдено. Створюю новий..."
        echo ""
        echo "Введіть email для SSH ключа:"
        read SSH_EMAIL
        
        ssh-keygen -t ed25519 -C "$SSH_EMAIL" -f ~/.ssh/id_ed25519 -N ""
        
        echo ""
        echo "SSH ключ створено!"
    else
        echo "Використовую існуючий SSH ключ..."
    fi
    
    echo ""
    echo "Ваш публічний SSH ключ:"
    echo "=========================================="
    if [ -f ~/.ssh/id_ed25519.pub ]; then
        cat ~/.ssh/id_ed25519.pub
    elif [ -f ~/.ssh/id_rsa.pub ]; then
        cat ~/.ssh/id_rsa.pub
    fi
    echo "=========================================="
    echo ""
    echo "Скопіюйте ключ вище та додайте його на GitHub:"
    echo "1. Перейдіть на https://github.com/settings/keys"
    echo "2. Натисніть 'New SSH key'"
    echo "3. Вкажіть назву (наприклад: 'praktik-server')"
    echo "4. Вставте скопійований ключ"
    echo "5. Натисніть 'Add SSH key'"
    echo ""
    echo "Після додавання ключа натисніть Enter для продовження..."
    read
    
    echo ""
    echo "Тестування підключення до GitHub..."
    ssh -T git@github.com || true
    
    echo ""
    echo "Тепер ви можете клонувати репозиторій через SSH:"
    echo "git clone git@github.com:prosvitco-artur/praktik-new.git"
    
    echo ""
    echo "Або змінити URL існуючого репозиторію:"
    echo "git remote set-url origin git@github.com:prosvitco-artur/praktik-new.git"
else
    echo "Невірний вибір. Вихід."
    exit 1
fi

echo ""
echo "=========================================="
echo "Налаштування завершено!"
echo "=========================================="

