# План реалізації SEO покращень

## Короткий опис проекту

**Praktik** - WordPress тема для агентства нерухомості з:
- 7 кастомними post types (кімнати, квартири, будинки, ділянки, гаражі, комерційна, дачі)
- Складною системою фільтрів та пошуку
- Інтеграцією з Airtable через AirWP Sync
- Системою обраного для користувачів
- Mobile-first адаптивним дизайном

---

## Топ-10 SEO покращень для реалізації

### 1. Open Graph та Twitter Cards Meta Tags

**Проблема:** Відсутні meta теги для соціальних мереж, що погіршує відображення при шарингу.

**Рішення:** Додати динамічні OG теги для об'єктів нерухомості.

**Файл:** `app/seo.php` (новий файл)

```php
<?php

namespace App;

function get_property_og_image($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $gallery = get_property_gallery($post_id);
    if (!empty($gallery) && isset($gallery[0]['url'])) {
        return $gallery[0]['url'];
    }
    
    $thumbnail_id = get_post_thumbnail_id($post_id);
    if ($thumbnail_id) {
        return wp_get_attachment_image_url($thumbnail_id, 'large');
    }
    
    return get_template_directory_uri() . '/resources/images/default-property.jpg';
}

function get_property_meta_description($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $custom_desc = carbon_get_post_meta($post_id, 'property_description');
    if (!empty($custom_desc)) {
        return wp_strip_all_tags($custom_desc);
    }
    
    $meta = get_property_meta($post_id);
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

add_action('wp_head', function() {
    if (is_singular()) {
        $post_id = get_the_ID();
        $post_type = get_post_type($post_id);
        
        if (in_array($post_type, array_keys(get_property_post_types()))) {
            $title = get_the_title();
            $description = get_property_meta_description($post_id);
            $image = get_property_og_image($post_id);
            $url = get_permalink();
            
            echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
            echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
            echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
            echo '<meta property="og:type" content="website">' . "\n";
            echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
            
            echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
            echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
            echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
            echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "\n";
        }
    }
}, 5);
```

---

### 2. JSON-LD структуровані дані (Schema.org)

**Проблема:** Відсутні структуровані дані для об'єктів нерухомості.

**Рішення:** Додати Product/Offer schema для об'єктів.

**Додати в `app/seo.php`:**

```php
function get_property_schema($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $meta = get_property_meta($post_id);
    $post_type = get_post_type($post_id);
    $property_type_label = get_property_type_label($post_type);
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => get_the_title($post_id),
        'description' => get_property_meta_description($post_id),
        'image' => get_property_og_image($post_id),
        'offers' => [
            '@type' => 'Offer',
            'price' => $meta['price'] ?? 0,
            'priceCurrency' => 'USD',
            'availability' => 'https://schema.org/InStock',
            'url' => get_permalink($post_id),
        ],
        'category' => $property_type_label,
    ];
    
    if (!empty($meta['area'])) {
        $schema['additionalProperty'][] = [
            '@type' => 'PropertyValue',
            'name' => __('Area', 'praktik'),
            'value' => $meta['area'] . ' m²',
        ];
    }
    
    if (!empty($meta['rooms'])) {
        $schema['additionalProperty'][] = [
            '@type' => 'PropertyValue',
            'name' => __('Rooms', 'praktik'),
            'value' => $meta['rooms'],
        ];
    }
    
    $location = [];
    if (!empty($meta['city'])) {
        $location[] = $meta['city'];
    }
    if (!empty($meta['street'])) {
        $location[] = $meta['street'];
    }
    
    if (!empty($location)) {
        $schema['additionalProperty'][] = [
            '@type' => 'PropertyValue',
            'name' => __('Location', 'praktik'),
            'value' => implode(', ', $location),
        ];
    }
    
    return $schema;
}

add_action('wp_footer', function() {
    if (is_singular()) {
        $post_id = get_the_ID();
        $post_type = get_post_type($post_id);
        
        if (in_array($post_type, array_keys(get_property_post_types()))) {
            $schema = get_property_schema($post_id);
            echo '<script type="application/ld+json">' . "\n";
            echo wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            echo "\n" . '</script>' . "\n";
        }
    }
}, 5);
```

---

### 3. Breadcrumbs (Хлібні крихти)

**Проблема:** Відсутня навігація breadcrumbs.

**Рішення:** Створити компонент breadcrumbs.

**Файл:** `app/helpers.php` (додати функцію)

```php
function get_breadcrumbs() {
    $breadcrumbs = [];
    $breadcrumbs[] = [
        'title' => __('Home', 'praktik'),
        'url' => home_url('/'),
    ];
    
    if (is_singular()) {
        $post_type = get_post_type();
        
        if (in_array($post_type, array_keys(get_property_post_types()))) {
            $post_type_label = get_property_type_label($post_type);
            $archive_url = get_post_type_archive_link($post_type);
            
            $breadcrumbs[] = [
                'title' => $post_type_label,
                'url' => $archive_url,
            ];
            
            $breadcrumbs[] = [
                'title' => get_the_title(),
                'url' => get_permalink(),
            ];
        } elseif (is_single()) {
            $breadcrumbs[] = [
                'title' => get_the_title(),
                'url' => get_permalink(),
            ];
        }
    } elseif (is_post_type_archive()) {
        $post_type = get_post_type();
        $post_type_label = get_property_type_label($post_type);
        
        $breadcrumbs[] = [
            'title' => $post_type_label,
            'url' => get_post_type_archive_link($post_type),
        ];
    } elseif (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        $breadcrumbs[] = [
            'title' => $term->name,
            'url' => get_term_link($term),
        ];
    }
    
    return $breadcrumbs;
}

function get_breadcrumbs_schema() {
    $breadcrumbs = get_breadcrumbs();
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [],
    ];
    
    foreach ($breadcrumbs as $index => $crumb) {
        $schema['itemListElement'][] = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $crumb['title'],
            'item' => $crumb['url'],
        ];
    }
    
    return $schema;
}
```

**Файл:** `resources/views/components/breadcrumbs.blade.php` (новий)

```blade
@php
  $breadcrumbs = get_breadcrumbs();
  if (empty($breadcrumbs) || count($breadcrumbs) <= 1) {
    return;
  }
@endphp

<nav class="breadcrumbs" aria-label="{{ __('Breadcrumb', 'praktik') }}">
  <ol class="flex flex-wrap items-center gap-2 text-sm">
    @foreach ($breadcrumbs as $index => $crumb)
      <li class="flex items-center">
        @if ($index > 0)
          <x-icon name="chevron-right" class="w-4 h-4 mx-2 text-neutral-400" />
        @endif
        @if ($index === count($breadcrumbs) - 1)
          <span class="text-neutral-600">{{ $crumb['title'] }}</span>
        @else
          <a href="{{ $crumb['url'] }}" class="text-secondary-500 hover:text-secondary-600">
            {{ $crumb['title'] }}
          </a>
        @endif
      </li>
    @endforeach
  </ol>
</nav>

@php
  $schema = get_breadcrumbs_schema();
@endphp
<script type="application/ld+json">
{!! wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
```

---

### 4. Meta Description

**Проблема:** Немає кастомних meta descriptions.

**Рішення:** Додати поле в Carbon Fields та функцію для виводу.

**Додати в `app/fields/property-fields.php`:**

```php
// В методі get_content_fields()
Field::make('textarea', 'property_meta_description', __('Meta Description', 'praktik'))
    ->set_help_text(__('SEO meta description (recommended: 150-160 characters)', 'praktik'))
    ->set_attribute('maxlength', 160),
```

**Додати в `app/seo.php`:**

```php
add_action('wp_head', function() {
    if (is_singular()) {
        $post_id = get_the_ID();
        $post_type = get_post_type($post_id);
        
        if (in_array($post_type, array_keys(get_property_post_types()))) {
            $meta_desc = carbon_get_post_meta($post_id, 'property_meta_description');
            
            if (empty($meta_desc)) {
                $meta_desc = get_property_meta_description($post_id);
            }
            
            if (!empty($meta_desc)) {
                echo '<meta name="description" content="' . esc_attr($meta_desc) . '">' . "\n";
            }
        }
    } elseif (is_post_type_archive()) {
        $post_type = get_post_type();
        if (in_array($post_type, array_keys(get_property_post_types()))) {
            $description = get_post_type_object($post_type)->description;
            if (!empty($description)) {
                echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
            }
        }
    }
}, 1);
```

---

### 5. Canonical URLs

**Проблема:** Можливе дублювання контенту через фільтри.

**Рішення:** Додати canonical теги.

**Додати в `app/seo.php`:**

```php
add_action('wp_head', function() {
    $canonical = '';
    
    if (is_singular()) {
        $canonical = get_permalink();
    } elseif (is_post_type_archive()) {
        $canonical = get_post_type_archive_link(get_post_type());
    } elseif (is_home()) {
        $canonical = home_url('/');
    }
    
    if (!empty($canonical)) {
        echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n";
    }
}, 1);
```

---

### 6. Alt тексти для зображень

**Проблема:** Можливо відсутні alt тексти.

**Рішення:** Автоматична генерація alt текстів.

**Додати в `app/helpers.php`:**

```php
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

### 7. Robots Meta Tags

**Проблема:** Немає контролю над індексацією.

**Рішення:** Додати robots meta для служебних сторінок.

**Додати в `app/seo.php`:**

```php
add_action('wp_head', function() {
    if (is_page_template('template-favorites.php')) {
        echo '<meta name="robots" content="noindex, nofollow">' . "\n";
    }
    
    if (is_search()) {
        echo '<meta name="robots" content="noindex, follow">' . "\n";
    }
}, 1);
```

---

### 8. Organization Schema для головної

**Додати в `app/seo.php`:**

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
        
        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        echo "\n" . '</script>' . "\n";
    }
}, 5);
```

---

### 9. Підключення SEO функцій

**Додати в `functions.php`:**

```php
collect(['seo'])->each(function ($file) {
    if (!locate_template($file = "app/{$file}.php", true, true)) {
        wp_die(
            sprintf(__('Error locating <code>%s</code> for inclusion.', 'praktik'), $file)
        );
    }
});
```

---

### 10. XML Sitemap

**Рішення:** Використати WordPress нативний sitemap (WP 5.5+) або додати кастомний.

**Додати в `app/setup.php`:**

```php
add_filter('wp_sitemaps_post_types', function($post_types) {
    $property_types = array_keys(get_property_post_types());
    foreach ($property_types as $type) {
        if (isset($post_types[$type])) {
            $post_types[$type] = true;
        }
    }
    return $post_types;
});
```

---

## Пріоритети реалізації

1. **Негайно:** Open Graph, Meta Description, Canonical URLs
2. **Важливо:** JSON-LD структуровані дані, Breadcrumbs
3. **Бажано:** Alt тексти, Robots Meta, Organization Schema

---

## Тестування

Після реалізації перевірити:
- [Google Rich Results Test](https://search.google.com/test/rich-results)
- [Schema.org Validator](https://validator.schema.org/)
- [Facebook Sharing Debugger](https://developers.facebook.com/tools/debug/)
- [Twitter Card Validator](https://cards-dev.twitter.com/validator)

---

*Документ створено: 2025-01-27*

