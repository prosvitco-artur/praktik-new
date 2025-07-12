# Переклади теми Praktik

Ця папка містить файли перекладів для теми Praktik.

## Структура файлів

- `praktik.pot` - головний файл перекладів (шаблон)
- `praktik-uk.po` - українські переклади
- `praktik-en_US.po` - англійські переклади

## Як додати новий переклад

1. Додайте новий рядок у коді з функцією `__()`:
   ```php
   echo __( 'New String', 'praktik' );
   ```

2. Оновіть файл `praktik.pot`:
   ```bash
   xgettext -o languages/praktik.pot --from-code=UTF-8 -L PHP inc/*.php
   ```

3. Оновіть файли `.po`:
   ```bash
   msgmerge -U languages/praktik-uk.po languages/praktik.pot
   msgmerge -U languages/praktik-en_US.po languages/praktik.pot
   ```

4. Скомпілюйте файли `.mo`:
   ```bash
   msgfmt -o languages/praktik-uk.mo languages/praktik-uk.po
   msgfmt -o languages/praktik-en_US.mo languages/praktik-en_US.po
   ```

## Підтримувані мови

- Українська (uk)
- Англійська (en_US)

## Примітки

- Всі переклади автоматично завантажуються при ініціалізації теми
- Текстовий домен: `praktik`
- Файли `.mo` генеруються автоматично при збереженні `.po` файлів 