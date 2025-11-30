# SEO покращення з Yoast SEO

## Поточний стан

Ви використовуєте **Yoast SEO** плагін, який вже надає багато SEO функцій. Цей документ описує, що вже покриває Yoast, та що потрібно додатково налаштувати або покращити.

---

## Що вже покриває Yoast SEO

### ✅ Автоматично надається:
- ✅ **Meta Description** - через Yoast метабокс
- ✅ **Open Graph теги** - автоматично генеруються
- ✅ **Twitter Cards** - автоматично генеруються
- ✅ **Canonical URLs** - автоматично додаються
- ✅ **XML Sitemap** - автоматично генерується
- ✅ **Robots Meta** - контроль через Yoast
- ✅ **Title оптимізація** - через Yoast шаблони
- ✅ **Breadcrumbs** - якщо увімкнено в налаштуваннях
- ✅ **Schema.org структуровані дані** - в новіших версіях Yoast

---

## Що потрібно налаштувати в Yoast

### 1. Реєстрація кастомних post types в Yoast

**Проблема:** Yoast може не розпізнавати кастомні post types автоматично.

**Рішення:** Додати підтримку в `app/post-types.php`:

```php
add_action('init', function () {
    // ... існуючий код реєстрації post types ...
    
    // Додати підтримку Yoast SEO для кастомних post types
    $property_post_types = array_keys(get_property_post_types());
    foreach ($property_post_types as $post_type) {
        add_post_type_support($post_type, 'yoast-seo');
    }
}, 20);
```

### 2. Налаштування Yoast для кастомних post types

**В адмін-панелі Yoast:**
1. Перейдіть до **SEO → Settings → Post Types**
2. Переконайтеся, що всі ваші кастомні post types увімкнені
3. Налаштуйте **Search appearance** для кожного типу

### 3. Налаштування XML Sitemap

**В адмін-панелі Yoast:**
1. Перейдіть до **SEO → Settings → General → Features**
2. Увімкніть **XML sitemaps**
3. Перейдіть до **SEO → Settings → General → XML Sitemaps**
4. Переконайтеся, що всі кастомні post types включені

---

## Додаткові покращення (не покриваються Yoast)

### 1. Спеціалізовані Schema.org структуровані дані для нерухомості

**Проблема:** Yoast генерує загальні структуровані дані, але для нерухомості потрібні спеціалізовані типи (RealEstateListing, Offer).

**Рішення:** Додати кастомні структуровані дані.

**Файл:** `app/seo-property-schema.php` (новий)

```php
<?php

namespace App;

function get_property_schema($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post_type = get_post_type($post_id);
    if (!in_array($post_type, array_keys(get_property_post_types()))) {
        return null;
    }
    
    $meta = get_property_meta($post_id);
    $gallery = get_property_gallery($post_id);
    $images = array_map(function($img) {
        return $img['url'];
    }, $gallery);
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'RealEstateListing',
        'name' => get_the_title($post_id),
        'description' => get_property_meta_description($post_id),
        'image' => !empty($images) ? $images : [get_property_og_image($post_id)],
        'url' => get_permalink($post_id),
        'offers' => [
            '@type' => 'Offer',
            'price' => $meta['price'] ?? 0,
            'priceCurrency' => 'USD',
            'availability' => 'https://schema.org/InStock',
            'url' => get_permalink($post_id),
        ],
    ];
    
    // Додати деталі про нерухомість
    $additional_properties = [];
    
    if (!empty($meta['area'])) {
        $additional_properties[] = [
            '@type' => 'PropertyValue',
            'name' => __('Total Area', 'praktik'),
            'value' => $meta['area'] . ' m²',
        ];
    }
    
    if (!empty($meta['rooms'])) {
        $additional_properties[] = [
            '@type' => 'PropertyValue',
            'name' => __('Number of Rooms', 'praktik'),
            'value' => $meta['rooms'],
        ];
    }
    
    if (!empty($meta['floor'])) {
        $additional_properties[] = [
            '@type' => 'PropertyValue',
            'name' => __('Floor', 'praktik'),
            'value' => $meta['floor'],
        ];
    }
    
    if (!empty($meta['year_built'])) {
        $additional_properties[] = [
            '@type' => 'PropertyValue',
            'name' => __('Year Built', 'praktik'),
            'value' => $meta['year_built'],
        ];
    }
    
    if (!empty($additional_properties)) {
        $schema['additionalProperty'] = $additional_properties;
    }
    
    // Додати адресу
    $address_parts = [];
    if (!empty($meta['city'])) {
        $address_parts[] = $meta['city'];
    }
    if (!empty($meta['street'])) {
        $address_parts[] = $meta['street'];
    }
    
    if (!empty($address_parts)) {
        $schema['address'] = [
            '@type' => 'PostalAddress',
            'addressLocality' => implode(', ', $address_parts),
            'addressCountry' => 'UA',
        ];
    }
    
    return $schema;
}

add_action('wp_footer', function() {
    if (is_singular()) {
        $post_id = get_the_ID();
        $schema = get_property_schema($post_id);
        
        if ($schema) {
            echo '<script type="application/ld+json">' . "\n";
            echo wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            echo "\n" . '</script>' . "\n";
        }
    }
}, 5);
```

**Додати в `functions.php`:**

```php
collect(['seo-property-schema'])->each(function ($file) {
    if (!locate_template($file = "app/{$file}.php", true, true)) {
        wp_die(
            sprintf(__('Error locating <code>%s</code> for inclusion.', 'praktik'), $file)
        );
    }
});
```

---

### 2. Organization Schema для головної сторінки

**Проблема:** Yoast генерує Organization, але можна додати RealEstateAgent тип.

**Рішення:** Додати в `app/seo-property-schema.php`:

```php
add_action('wp_footer', function() {
    if (is_front_page() || is_home()) {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'RealEstateAgent',
            'name' => get_bloginfo('name'),
            'url' => home_url('/'),
            'logo' => wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full'),
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => carbon_get_theme_option('praktik_city') ?: 'Чернігів',
                'addressCountry' => 'UA',
            ],
            'telephone' => carbon_get_theme_option('praktik_phone'),
            'email' => carbon_get_theme_option('praktik_email'),
        ];
        
        $social_links = get_social_links();
        if (has_social_links()) {
            $schema['sameAs'] = array_filter(array_values($social_links));
        }
        
        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        echo "\n" . '</script>' . "\n";
    }
}, 5);
```

---

### 3. Покращення Open Graph для об'єктів нерухомості

**Проблема:** Yoast генерує базові OG теги, але можна додати додаткові поля.

**Рішення:** Фільтр Yoast для кастомних значень.

**Додати в `app/seo-yoast-filters.php`:**

```php
<?php

namespace App;

add_filter('wpseo_opengraph_title', function($title) {
    if (is_singular()) {
        $post_type = get_post_type();
        if (in_array($post_type, array_keys(get_property_post_types()))) {
            $meta = get_property_meta();
            $parts = [get_the_title()];
            
            if (!empty($meta['price'])) {
                $parts[] = format_property_price($meta['price']);
            }
            if (!empty($meta['city'])) {
                $parts[] = $meta['city'];
            }
            
            return implode(' • ', $parts);
        }
    }
    return $title;
}, 10, 1);

add_filter('wpseo_opengraph_desc', function($description) {
    if (is_singular()) {
        $post_type = get_post_type();
        if (in_array($post_type, array_keys(get_property_post_types()))) {
            $meta = get_property_meta();
            $parts = [];
            
            if (!empty($meta['rooms'])) {
                $parts[] = $meta['rooms'] . ' ' . __('rooms', 'praktik');
            }
            if (!empty($meta['area'])) {
                $parts[] = $meta['area'] . ' m²';
            }
            if (!empty($meta['price'])) {
                $parts[] = format_property_price($meta['price']);
            }
            if (!empty($meta['city'])) {
                $parts[] = $meta['city'];
            }
            
            return implode(' • ', $parts);
        }
    }
    return $description;
}, 10, 1);

add_filter('wpseo_opengraph_image', function($image) {
    if (is_singular()) {
        $post_type = get_post_type();
        if (in_array($post_type, array_keys(get_property_post_types()))) {
            $gallery = get_property_gallery();
            if (!empty($gallery) && isset($gallery[0]['url'])) {
                return $gallery[0]['url'];
            }
        }
    }
    return $image;
}, 10, 1);
```

**Додати в `functions.php`:**

```php
collect(['seo-yoast-filters'])->each(function ($file) {
    if (!locate_template($file = "app/{$file}.php", true, true)) {
        wp_die(
            sprintf(__('Error locating <code>%s</code> for inclusion.', 'praktik'), $file)
        );
    }
});
```

---

### 4. Breadcrumbs для кастомних post types

**Проблема:** Yoast breadcrumbs можуть не працювати коректно з кастомними post types.

**Рішення:** Налаштувати в Yoast та додати кастомний компонент.

**В адмін-панелі Yoast:**
1. Перейдіть до **SEO → Settings → Advanced → Breadcrumbs**
2. Увімкніть breadcrumbs
3. Налаштуйте шаблони для кастомних post types

**Альтернатива:** Використати кастомний компонент breadcrumbs (якщо Yoast не підходить).

---

### 5. Оптимізація Alt текстів для зображень

**Проблема:** Yoast не генерує автоматично alt тексти.

**Рішення:** Додати автоматичну генерацію при завантаженні.

**Додати в `app/seo-images.php`:**

```php
<?php

namespace App;

add_filter('wp_generate_attachment_metadata', function($metadata, $attachment_id) {
    $post_id = wp_get_post_parent_id($attachment_id);
    
    if ($post_id) {
        $post_type = get_post_type($post_id);
        
        if (in_array($post_type, array_keys(get_property_post_types()))) {
            $alt_text = get_property_image_alt($post_id, $attachment_id);
            
            if (empty(get_post_meta($attachment_id, '_wp_attachment_image_alt', true))) {
                update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt_text);
            }
        }
    }
    
    return $metadata;
}, 10, 2);

function get_property_image_alt($post_id = null, $image_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    if ($image_id) {
        $alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
        if (!empty($alt)) {
            return $alt;
        }
    }
    
    $meta = get_property_meta($post_id);
    $parts = [get_the_title($post_id)];
    
    if (!empty($meta['rooms'])) {
        $parts[] = $meta['rooms'] . ' ' . __('rooms', 'praktik');
    }
    if (!empty($meta['area'])) {
        $parts[] = $meta['area'] . ' m²';
    }
    if (!empty($meta['city'])) {
        $parts[] = $meta['city'];
    }
    
    return implode(', ', $parts);
}
```

---

### 6. Налаштування Yoast для архівів

**В адмін-панелі Yoast:**
1. Перейдіть до **SEO → Settings → Search Appearance → Archives**
2. Налаштуйте meta для кожного типу архіву
3. Додайте описи для архівів кастомних post types

---

## Чек-лист налаштування Yoast

### Обов'язкові налаштування:

- [ ] Увімкнути XML Sitemap в Yoast
- [ ] Додати кастомні post types до sitemap
- [ ] Налаштувати Search Appearance для кожного post type
- [ ] Налаштувати шаблони Title та Meta Description
- [ ] Увімкнути Open Graph та Twitter Cards
- [ ] Налаштувати Social Media (Facebook, Twitter)
- [ ] Увімкнути Breadcrumbs (якщо потрібно)
- [ ] Налаштувати Schema.org структуровані дані

### Рекомендовані налаштування:

- [ ] Увімкнути Advanced settings
- [ ] Налаштувати Redirects (якщо потрібно)
- [ ] Налаштувати Internal linking suggestions
- [ ] Увімкнути SEO analysis для редакторів
- [ ] Налаштувати Focus keywords для типових об'єктів

---

## Додаткові покращення (опціонально)

### 1. Lazy Loading для зображень

Yoast не надає lazy loading, але WordPress 5.5+ має нативну підтримку.

**Перевірити:** Чи увімкнено в `app/setup.php`:

```php
add_filter('wp_lazy_loading_enabled', '__return_true');
```

### 2. Оптимізація швидкості

- Використовувати кешування (WP Super Cache, W3 Total Cache)
- Оптимізувати зображення (Smush, ShortPixel)
- Мінімізувати CSS/JS (Yoast має опції)

### 3. Моніторинг

- Підключити Google Search Console
- Підключити Google Analytics
- Використовувати Yoast SEO Dashboard

---

## Тестування після налаштування

1. **Перевірити структуровані дані:**
   - [Google Rich Results Test](https://search.google.com/test/rich-results)
   - [Schema.org Validator](https://validator.schema.org/)

2. **Перевірити Open Graph:**
   - [Facebook Sharing Debugger](https://developers.facebook.com/tools/debug/)
   - [Twitter Card Validator](https://cards-dev.twitter.com/validator)

3. **Перевірити Sitemap:**
   - Відкрити `/sitemap_index.xml`
   - Перевірити наявність кастомних post types

4. **Перевірити індексацію:**
   - Google Search Console
   - Перевірити кількість індексованих сторінок

---

## Важливі примітки

1. **Не дублювати функціонал:** Якщо Yoast вже надає функцію, не додавайте власну реалізацію
2. **Використовувати фільтри Yoast:** Замість заміни функцій, використовуйте хуки Yoast
3. **Тестувати зміни:** Завжди перевіряйте після додавання нових функцій
4. **Оновлювати Yoast:** Тримайте плагін актуальним

---

## Пріоритети реалізації

### Пріоритет 1 (Негайно):
1. Налаштувати Yoast для кастомних post types
2. Увімкнути XML Sitemap
3. Налаштувати Open Graph та Twitter Cards

### Пріоритет 2 (Важливо):
1. Додати RealEstateListing Schema
2. Покращити OG теги через фільтри
3. Налаштувати Breadcrumbs

### Пріоритет 3 (Бажано):
1. Оптимізація Alt текстів
2. Organization Schema
3. Моніторинг та аналітика

---

*Документ створено: 2025-01-27*
*Версія Yoast SEO: Рекомендується остання версія*

