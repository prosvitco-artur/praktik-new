# Інструкція з перенесення SVG Use системи на інший проєкт

## Огляд системи

Ця тема використовує SVG sprite систему з `<use>` елементами для виведення іконок. Система складається з:
- SVG файлів в `resources/images/icons/`
- PHP функцій для генерації sprite
- Blade компонента `<x-icon>`
- CSS стилів для іконок

## Крок 1: Копіювання SVG файлів

Скопіюйте всі SVG файли з директорії:
```
resources/images/icons/*.svg
```

Вставте їх у новий проєкт у відповідну директорію (наприклад, `assets/icons/` або `resources/images/icons/`).

## Крок 2: Додавання PHP функцій

Додайте наступні функції у файл `functions.php` або окремий файл (наприклад, `app/helpers.php`):

```php
function svg_sprite_path() {
    return get_template_directory() . '/resources/images/icons';
}

function get_svg_icons() {
    $icons_path = svg_sprite_path();
    
    if (!file_exists($icons_path)) {
        return [];
    }
    
    $icons = [];
    $files = glob($icons_path . '/*.svg');
    
    foreach ($files as $file) {
        $icon_name = basename($file, '.svg');
        if (!file_exists($file)) {
            continue;
        }
        $content = file_get_contents($file);
        
        if (preg_match('/<svg[^>]*>(.*?)<\/svg>/is', $content, $matches)) {
            $svg_content = $matches[1];
            
            if (preg_match('/<svg[^>]*viewBox=["\']([^"\']+)["\'][^>]*>/i', $content, $viewbox_matches)) {
                $viewbox = $viewbox_matches[1];
            } else {
                $viewbox = '0 0 24 24';
            }
            
            $icons[$icon_name] = [
                'content' => $svg_content,
                'viewbox' => $viewbox
            ];
        }
    }
    
    return $icons;
}

function generate_svg_sprite() {
    $icons = get_svg_icons();
    
    if (empty($icons)) {
        return '';
    }
    
    $sprite = '<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">';
    
    foreach ($icons as $name => $data) {
        $sprite .= sprintf(
            '<symbol id="icon-%s" viewBox="%s">%s</symbol>',
            esc_attr($name),
            esc_attr($data['viewbox']),
            $data['content']
        );
    }
    
    $sprite .= '</svg>';
    
    return $sprite;
}

add_action('wp_footer', function() {
    echo generate_svg_sprite();
}, 999);

add_action('admin_footer', function() {
    echo generate_svg_sprite();
}, 999);
```

**Важливо:** Змініть шлях у функції `svg_sprite_path()` відповідно до структури вашого проєкту.

## Крок 3: Створення Blade компонента

Створіть файл `resources/views/components/icon.blade.php`:

```blade
@props([
    'name' => '',
    'class' => ''
])

@php
$classes = 'icon icon-' . $name;
if ($class) {
    $classes .= ' ' . $class;
}
@endphp

<svg class="{{ $classes }}" aria-hidden="true" focusable="false">
    <use href="#icon-{{ $name }}"></use>
</svg>
```

Якщо ви не використовуєте Blade, створіть PHP функцію:

```php
function svg_icon($name, $class = '', $attr = []) {
    $classes = ['icon', "icon-{$name}"];
    
    if ($class) {
        $classes[] = $class;
    }
    
    $attributes = [
        'class' => implode(' ', $classes),
        'aria-hidden' => 'true',
        'focusable' => 'false'
    ];
    
    $attributes = array_merge($attributes, $attr);
    
    $attr_string = '';
    foreach ($attributes as $key => $value) {
        $attr_string .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
    }
    
    return sprintf(
        '<svg%s><use href="#icon-%s"></use></svg>',
        $attr_string,
        esc_attr($name)
    );
}
```

## Крок 4: Додавання CSS стилів

Додайте стилі для іконок у ваш CSS файл:

```css
.icon {
  display: var(--icon-display, block);
  width: var(--icon-width, 1.5rem);
  height: var(--icon-height, 1.5rem);
  max-width: var(--icon-max-width, 100%);
  margin: var(--icon-margin, 0);
  font-size: var(--icon-font-size, 1.5rem);
  color: var(--icon-color, inherit);
  fill: currentColor;
  flex-shrink: 0;
  pointer-events: none;
}
```

## Крок 5: Використання

### З Blade компонентом:
```blade
<x-icon name="search" class="w-5 h-5" />
```

### З PHP функцією:
```php
echo svg_icon('search', 'w-5 h-5');
```

### З кастомними атрибутами:
```php
echo svg_icon('search', 'w-5 h-5', ['data-test' => 'value']);
```

## Крок 6: Налаштування шляхів

Якщо структура вашого проєкту відрізняється, змініть шлях у функції `svg_sprite_path()`:

```php
function svg_sprite_path() {
    // Приклад для іншої структури:
    return get_template_directory() . '/assets/icons';
    // або
    return get_stylesheet_directory() . '/icons';
}
```

## Переваги цієї системи

1. **Один раз завантажується** - всі іконки в одному sprite
2. **Кешування** - браузер кешує один файл
3. **Легке стилізування** - через CSS змінні
4. **Доступність** - правильні ARIA атрибути
5. **Гнучкість** - легко додавати нові іконки

## Додавання нових іконок

1. Додайте SVG файл у директорію іконок
2. Назвіть файл відповідно до назви іконки (наприклад, `new-icon.svg`)
3. Використовуйте: `<x-icon name="new-icon" />`

## Формат SVG файлів

SVG файли повинні мати правильний формат:

```svg
<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="..." />
</svg>
```

Важливо: SVG повинен містити атрибут `viewBox` для правильної роботи.

## Примітки

- Sprite генерується динамічно при кожному завантаженні сторінки
- Для оптимізації можна додати кешування результату `generate_svg_sprite()`
- Іконки автоматично підтримують `currentColor` для зміни кольору через CSS





