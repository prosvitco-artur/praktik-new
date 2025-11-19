<?php

namespace App\View\Components;

use Walker_Nav_Menu;

class MobileMenuWalker extends Walker_Nav_Menu
{
    /**
     * Start the element output.
     */
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"mobile-submenu hidden pl-4\">\n";
    }

    /**
     * Start the element.
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'mobile-menu-item';
        
        // Check if item has children
        $has_children = in_array('menu-item-has-children', $classes);
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= $indent . '<li' . $class_names . '>';

        $atts = [];
        $atts['href'] = !empty($item->url) ? $item->url : '';
        $atts['class'] = 'mobile-menu-link flex items-center justify-between text-neutral-900 px-3 py-2 mb-2';
        
        if ($depth === 0) {
            $atts['class'] .= ' font-medium';
        }

        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $title = apply_filters('the_title', $item->title, $item->ID);
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

        if ($has_children) {
            // Parent item with submenu
            $output .= '<button type="button" class="mobile-menu-link mobile-submenu-toggle flex items-center justify-between w-full px-3 py-2 mb-2';
            if ($depth === 0) {
                $output .= ' font-medium';
            }
            $output .= '" data-submenu-toggle>';
            $output .= '<span>' . $title . '</span>';
            $output .= '<svg class="w-20px h-20px transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
            $output .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>';
            $output .= '</svg>';
            $output .= '</button>';
        } else {
            // Regular link
            $item_output = $args->before ?? '';
            $item_output .= '<a' . $attributes . '>';
            $item_output .= ($args->link_before ?? '') . '<span>' . $title . '</span>' . ($args->link_after ?? '');
            $item_output .= '</a>';
            $item_output .= $args->after ?? '';

            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
        }
    }

    /**
     * End the element.
     */
    public function end_el(&$output, $item, $depth = 0, $args = null)
    {
        $output .= "</li>\n";
    }
}

