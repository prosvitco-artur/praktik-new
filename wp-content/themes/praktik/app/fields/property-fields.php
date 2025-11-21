<?php

namespace App\Fields;

use Carbon_Fields\Container;
use Carbon_Fields\Field;
use App\Fields\PropertyFieldOptions;

/**
 * Register Carbon Fields for property post types
 */
class PropertyFields {
    
    /**
     * Register all property fields
     */
    public static function register() {
        $property_post_types = array_keys(\get_property_post_types());
        
        // Main container for all property types
        Container::make('post_meta', __('Property Details', 'praktik'))
            ->where('post_type', 'IN', $property_post_types)
            ->add_tab(__('Basic Information', 'praktik'), self::get_basic_fields())
            ->add_tab(__('Location', 'praktik'), self::get_location_fields())
            ->add_tab(__('Building Details', 'praktik'), self::get_building_fields())
            ->add_tab(__('Amenities', 'praktik'), self::get_amenities_fields())
            ->add_tab(__('Client Information', 'praktik'), self::get_client_fields())
            ->add_tab(__('Status & Sales', 'praktik'), self::get_status_fields())
            ->add_tab(__('Content & Media', 'praktik'), self::get_content_fields())
            ->add_tab(__('Additional Information', 'praktik'), self::get_additional_fields());
        
        // House-specific fields
        Container::make('post_meta', __('House Details', 'praktik'))
            ->where('post_type', '=', 'house')
            ->add_tab(__('House Specific', 'praktik'), self::get_house_specific_fields());
    }
    
    /**
     * Basic information fields
     */
    private static function get_basic_fields() {
        return [
            Field::make('text', 'property_code', __('Property Code', 'praktik'))
                ->set_help_text(__('Auto-generated property code (will be generated automatically on save)', 'praktik')),
            
            Field::make('select', 'property_type', __('Property Type', 'praktik'))
                ->set_options(PropertyFieldOptions::get_property_type_options())
                ->set_help_text(__('New building or secondary market', 'praktik')),
            
            Field::make('text', 'property_price', __('Price (USD)', 'praktik'))
                ->set_attribute('type', 'number')
                ->set_attribute('step', '1')
                ->set_attribute('min', '0')
                ->set_help_text(__('Price in USD', 'praktik')),
            
            Field::make('select', 'property_price_range', __('Price Range', 'praktik'))
                ->set_options(PropertyFieldOptions::get_price_range_options())
                ->set_help_text(__('Price range category', 'praktik')),
            
            Field::make('text', 'property_rooms', __('Number of Rooms', 'praktik'))
                ->set_attribute('type', 'number')
                ->set_attribute('min', '0')
                ->set_attribute('max', '20'),
            
            Field::make('text', 'property_area', __('Total Area (m²)', 'praktik'))
                ->set_attribute('type', 'number')
                ->set_attribute('step', '0.01')
                ->set_attribute('min', '0')
                ->set_help_text(__('Total area in square meters', 'praktik')),
            
            Field::make('text', 'property_kitchen_area', __('Kitchen Area (m²)', 'praktik'))
                ->set_attribute('type', 'number')
                ->set_attribute('step', '0.01')
                ->set_attribute('min', '0')
                ->set_help_text(__('Kitchen area in square meters', 'praktik')),
        ];
    }
    
    /**
     * Location fields
     */
    private static function get_location_fields() {
        return [
            Field::make('text', 'property_city', __('City', 'praktik'))
                ->set_default_value('Чернігів')
                ->set_help_text(__('City name', 'praktik')),
            
            Field::make('select', 'property_location_type', __('Location Type', 'praktik'))
                ->set_options(PropertyFieldOptions::get_location_type_options())
                ->set_help_text(__('City, Suburb, or Outside City', 'praktik')),
            
            Field::make('text', 'property_settlement', __('Settlement', 'praktik'))
                ->set_help_text(__('Settlement name (Населений пункт)', 'praktik')),
            
            Field::make('text', 'property_district', __('District', 'praktik'))
                ->set_help_text(__('District name (Район)', 'praktik')),
            
            Field::make('text', 'property_street', __('Street', 'praktik'))
                ->set_help_text(__('Street name (Вулиця)', 'praktik')),
            
            Field::make('text', 'property_building_number', __('Building Number', 'praktik'))
                ->set_help_text(__('Building/house number', 'praktik')),
        ];
    }
    
    /**
     * Building details fields
     */
    private static function get_building_fields() {
        return [
            Field::make('text', 'property_floor', __('Floor', 'praktik'))
                ->set_attribute('type', 'number')
                ->set_attribute('min', '0')
                ->set_attribute('max', '50'),
            
            Field::make('text', 'property_total_floors', __('Total Floors', 'praktik'))
                ->set_attribute('type', 'number')
                ->set_attribute('min', '1')
                ->set_attribute('max', '50'),
            
            Field::make('text', 'property_year_built', __('Year Built', 'praktik'))
                ->set_attribute('type', 'number')
                ->set_attribute('min', '1800')
                ->set_attribute('max', date('Y')),
            
            Field::make('select', 'property_condition', __('Condition', 'praktik'))
                ->set_options(PropertyFieldOptions::get_condition_options())
                ->set_help_text(__('Property condition state', 'praktik')),
            
            Field::make('select', 'property_bathroom', __('Bathroom', 'praktik'))
                ->set_options(PropertyFieldOptions::get_bathroom_options())
                ->set_help_text(__('Bathroom type', 'praktik')),
            
            Field::make('select', 'property_heating', __('Heating', 'praktik'))
                ->set_options(PropertyFieldOptions::get_heating_options())
                ->set_help_text(__('Heating type', 'praktik')),
            
            Field::make('select', 'property_furniture', __('Furniture', 'praktik'))
                ->set_options([
                    '' => __('Select Furniture', 'praktik'),
                    'furnished' => __('Furnished', 'praktik'),
                    'semi_furnished' => __('Semi-furnished', 'praktik'),
                    'unfurnished' => __('Unfurnished', 'praktik'),
                ]),
        ];
    }
    
    /**
     * Amenities fields
     */
    private static function get_amenities_fields() {
        return [
            Field::make('checkbox', 'property_parking', __('Parking', 'praktik')),
            
            Field::make('checkbox', 'property_balcony', __('Balcony', 'praktik')),
            
            Field::make('checkbox', 'property_elevator', __('Elevator', 'praktik')),
        ];
    }
    
    /**
     * Client information fields
     */
    private static function get_client_fields() {
        return [
            Field::make('text', 'property_client_name', __('Client Name', 'praktik'))
                ->set_help_text(__('Client name (Імя клієнта)', 'praktik')),
            
            Field::make('text', 'property_client_phone1', __('Phone 1', 'praktik'))
                ->set_attribute('type', 'tel')
                ->set_help_text(__('Primary phone number', 'praktik')),
            
            Field::make('text', 'property_client_phone2', __('Phone 2', 'praktik'))
                ->set_attribute('type', 'tel')
                ->set_help_text(__('Secondary phone number', 'praktik')),
            
            Field::make('text', 'property_client_phone3', __('Phone 3', 'praktik'))
                ->set_attribute('type', 'tel')
                ->set_help_text(__('Additional phone number', 'praktik')),
            
            Field::make('checkbox', 'property_hide_phones', __('Hide Phones', 'praktik'))
                ->set_help_text(__('Hide phone numbers from public display', 'praktik')),
            
            Field::make('select', 'property_realtor', __('Realtor', 'praktik'))
                ->set_options(self::get_realtor_options())
                ->set_help_text(__('Assigned realtor', 'praktik')),
        ];
    }
    
    /**
     * Status and sales fields
     */
    private static function get_status_fields() {
        return [
            Field::make('select', 'property_sale_status', __('Sale Status', 'praktik'))
                ->set_options(PropertyFieldOptions::get_sale_status_options())
                ->set_help_text(__('Current sale status', 'praktik')),
            
            Field::make('select', 'property_sales_funnel', __('Sales Funnel Stage', 'praktik'))
                ->set_options(PropertyFieldOptions::get_sales_funnel_options())
                ->set_help_text(__('Current stage in sales funnel', 'praktik')),
            
            Field::make('select', 'property_cooperation_status', __('Cooperation Status', 'praktik'))
                ->set_options(PropertyFieldOptions::get_cooperation_status_options())
                ->set_help_text(__('Cooperation status with client', 'praktik')),
            
            Field::make('date', 'property_registration_date', __('Registration Date', 'praktik'))
                ->set_help_text(__('Date when property was registered', 'praktik')),
            
            Field::make('date', 'property_last_contact_date', __('Last Contact Date', 'praktik'))
                ->set_help_text(__('Date of last contact with client', 'praktik')),
        ];
    }
    
    /**
     * Content and media fields
     */
    private static function get_content_fields() {
        return [
            Field::make('text', 'property_title', __('Title (for website)', 'praktik'))
                ->set_help_text(__('Custom title for website display', 'praktik')),
            
            Field::make('rich_text', 'property_description', __('Description (for website)', 'praktik'))
                ->set_help_text(__('Property description for website', 'praktik')),
            
            Field::make('media_gallery', 'property_gallery', __('Property Gallery', 'praktik'))
                ->set_help_text(__('Add property images', 'praktik')),
            
            Field::make('text', 'property_photos_count', __('Photos Count', 'praktik'))
                ->set_attribute('type', 'number')
                ->set_attribute('min', '0')
                ->set_help_text(__('Manual photo counter (optional)', 'praktik')),
            
            Field::make('text', 'property_video_link', __('Video Link', 'praktik'))
                ->set_attribute('type', 'url')
                ->set_attribute('placeholder', 'https://')
                ->set_help_text(__('Link to property video', 'praktik')),
            
            Field::make('select', 'property_shooting_status', __('Shooting Status', 'praktik'))
                ->set_options(PropertyFieldOptions::get_shooting_status_options())
                ->set_help_text(__('Photo/video shooting status', 'praktik')),
        ];
    }
    
    /**
     * Additional information fields
     */
    private static function get_additional_fields() {
        return [
            Field::make('select', 'property_source', __('Source', 'praktik'))
                ->set_options(PropertyFieldOptions::get_source_options())
                ->set_help_text(__('Where property was found', 'praktik')),
            
            Field::make('text', 'property_olx_link', __('OLX Link', 'praktik'))
                ->set_attribute('type', 'url')
                ->set_attribute('placeholder', 'https://')
                ->set_help_text(__('Link to OLX listing', 'praktik')),
            
            Field::make('text', 'property_olx_id', __('OLX ID', 'praktik'))
                ->set_attribute('type', 'number')
                ->set_help_text(__('OLX listing ID', 'praktik')),
            
            Field::make('textarea', 'property_notes', __('Notes', 'praktik'))
                ->set_help_text(__('Internal notes about the property', 'praktik')),
            
            Field::make('checkbox', 'property_featured', __('Featured', 'praktik'))
                ->set_help_text(__('Mark as featured property', 'praktik')),
            
            Field::make('checkbox', 'property_ready_for_export', __('Ready for Export', 'praktik'))
                ->set_help_text(__('Ready to export to website', 'praktik')),
        ];
    }
    
    /**
     * House-specific fields
     */
    private static function get_house_specific_fields() {
        return [
            Field::make('select', 'property_house_type', __('House Type', 'praktik'))
                ->set_options(PropertyFieldOptions::get_house_type_options())
                ->set_help_text(__('Type of house', 'praktik')),
            
            Field::make('text', 'property_plot_area', __('Plot Area (m²)', 'praktik'))
                ->set_attribute('type', 'number')
                ->set_attribute('step', '0.01')
                ->set_attribute('min', '0')
                ->set_help_text(__('Area of the plot in square meters', 'praktik')),
            
            Field::make('text', 'property_plot_area_sotok', __('Plot Area (соток)', 'praktik'))
                ->set_attribute('type', 'number')
                ->set_attribute('step', '0.01')
                ->set_attribute('min', '0')
                ->set_help_text(__('Area of the plot in соток', 'praktik')),
            
            Field::make('select', 'property_courtyard', __('Courtyard', 'praktik'))
                ->set_options(PropertyFieldOptions::get_courtyard_options())
                ->set_help_text(__('Courtyard type', 'praktik')),
            
            Field::make('select', 'property_sewage', __('Sewage', 'praktik'))
                ->set_options(PropertyFieldOptions::get_sewage_options())
                ->set_help_text(__('Sewage system type', 'praktik')),
            
            Field::make('select', 'property_building_material', __('Building Material', 'praktik'))
                ->set_options(PropertyFieldOptions::get_building_material_options())
                ->set_help_text(__('Building construction material', 'praktik')),
            
            Field::make('select', 'property_water', __('Water', 'praktik'))
                ->set_options(PropertyFieldOptions::get_utility_options())
                ->set_help_text(__('Water availability', 'praktik')),
            
            Field::make('select', 'property_gas', __('Gas', 'praktik'))
                ->set_options(PropertyFieldOptions::get_utility_options())
                ->set_help_text(__('Gas availability', 'praktik')),
            
            Field::make('select', 'property_electricity', __('Electricity', 'praktik'))
                ->set_options(PropertyFieldOptions::get_utility_options())
                ->set_help_text(__('Electricity availability', 'praktik')),
        ];
    }
    
    /**
     * Get realtor options
     * This should be populated from a custom post type or user list
     */
    private static function get_realtor_options() {
        // TODO: Implement dynamic realtor list from users or custom post type
        return [
            '' => __('Select Realtor', 'praktik'),
            'no_realtor' => __('No Realtor', 'praktik'),
            // Add more realtors dynamically
        ];
    }
}

