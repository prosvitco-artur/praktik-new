<?php

namespace App\View\Components;

use Walker_Nav_Menu;

class NavigationWalker extends Walker_Nav_Menu
{
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= '<li' . $id . $class_names . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

        // Add Tailwind classes based on menu location
        $menu_classes = 'text-gray-700 hover:text-blue-600 transition-colors font-medium';
        if (isset($args->menu_class) && strpos($args->menu_class, 'space-y-') !== false) {
            $menu_classes = 'block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors rounded-md';
        }

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . ' class="' . $menu_classes . '">';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
} 