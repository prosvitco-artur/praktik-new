<?php

namespace App\View\Components;

use Walker_Nav_Menu;

class DesktopMenuWalker extends Walker_Nav_Menu
{
    /**
     * Start the list before the elements are added.
     */
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);
        
        if ($depth === 0) {
            // First level submenu - dropdown
            $output .= "\n$indent<ul class=\"dropdown-menu absolute top-full left-0 bg-white shadow-lg rounded-lg border border-neutral-200 min-w-200px opacity-0 invisible transition-all duration-200 transform translate-y-2 z-50\">\n";
        } else {
            // Deeper levels
            $output .= "\n$indent<ul class=\"dropdown-submenu\">\n";
        }
    }

    /**
     * End the list after the elements are added.
     */
    public function end_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    /**
     * Start the element.
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'nav-item';
        
        // Check if item has children
        $has_children = in_array('menu-item-has-children', $classes);
        
        // Add dropdown classes for parent items
        if ($has_children && $depth === 0) {
            $classes[] = 'dropdown-parent';
        }
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= $indent . '<li' . $class_names . '>';

        $atts = [];
        $atts['href'] = !empty($item->url) ? $item->url : '#';
        
        if ($depth === 0) {
            // Top level items
            $atts['class'] = 'nav-link flex items-center gap-1 text-p1 text-neutral-900 hover:text-primary-600 transition-colors no-underline py-2 px-1';
        } else {
            // Submenu items
            $atts['class'] = 'dropdown-link block px-4 py-3 text-sm text-neutral-900 hover:text-primary-600 hover:bg-neutral-50 transition-colors no-underline';
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

        // Build the link
        $item_output = $args->before ?? '';
        $item_output .= '<a' . $attributes . '>';
        $item_output .= ($args->link_before ?? '') . $title . ($args->link_after ?? '');
        
        // Add dropdown arrow for parent items
        if ($has_children && $depth === 0) {
            $item_output .= '<svg class="w-4 h-4 ml-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
            $item_output .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>';
            $item_output .= '</svg>';
        }
        
        $item_output .= '</a>';
        $item_output .= $args->after ?? '';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    /**
     * End the element.
     */
    public function end_el(&$output, $item, $depth = 0, $args = null)
    {
        $output .= "</li>\n";
    }
}
