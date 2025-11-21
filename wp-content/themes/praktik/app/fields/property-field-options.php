<?php

namespace App\Fields;

/**
 * Options for property field selects
 */
class PropertyFieldOptions {
    
    /**
     * Get property type options (Вид обєкту)
     */
    public static function get_property_type_options() {
        return [
            '' => __('Select Property Type', 'praktik'),
            'new' => __('New Building', 'praktik'),
            'secondary' => __('Secondary Market', 'praktik'),
        ];
    }
    
    /**
     * Get house type options (Тип Будинку)
     */
    public static function get_house_type_options() {
        return [
            '' => __('Select House Type', 'praktik'),
            'house' => __('House', 'praktik'),
            'half_house' => __('Half House', 'praktik'),
            'dacha' => __('Dacha', 'praktik'),
        ];
    }
    
    /**
     * Get location type options (Розташування)
     */
    public static function get_location_type_options() {
        return [
            '' => __('Select Location', 'praktik'),
            'city' => __('City', 'praktik'),
            'suburb' => __('Suburb', 'praktik'),
            'outside_city' => __('Outside City', 'praktik'),
        ];
    }
    
    /**
     * Get condition options (Стан нерухомості)
     */
    public static function get_condition_options() {
        return [
            '' => __('Select Condition', 'praktik'),
            'author_project' => __('Author Project', 'praktik'),
            'euro_renovation' => __('Euro Renovation', 'praktik'),
            'cosmetic_repair' => __('Cosmetic Repair', 'praktik'),
            'living_condition' => __('Living Condition', 'praktik'),
            'after_builders' => __('After Builders', 'praktik'),
            'rough_finish' => __('Rough Finish', 'praktik'),
            'emergency' => __('Emergency Condition', 'praktik'),
        ];
    }
    
    /**
     * Get bathroom options (Санвузол)
     */
    public static function get_bathroom_options() {
        return [
            '' => __('Select Bathroom', 'praktik'),
            'combined' => __('Combined', 'praktik'),
            'separate' => __('Separate', 'praktik'),
            'none' => __('None', 'praktik'),
        ];
    }
    
    /**
     * Get heating options (Опалення)
     */
    public static function get_heating_options() {
        return [
            '' => __('Select Heating', 'praktik'),
            'autonomous' => __('Autonomous', 'praktik'),
            'tec' => __('TEC', 'praktik'),
            'heat_meters' => __('Heat Meters', 'praktik'),
            'electric' => __('Electric', 'praktik'),
            'stove' => __('Stove', 'praktik'),
            'none' => __('None', 'praktik'),
        ];
    }
    
    /**
     * Get sale status options (Статус продажу)
     */
    public static function get_sale_status_options() {
        return [
            '' => __('Select Sale Status', 'praktik'),
            'active' => __('Active Sale', 'praktik'),
            'postponed' => __('Sale Postponed', 'praktik'),
            'removed' => __('Removed from Sale', 'praktik'),
            'sold' => __('Property Sold', 'praktik'),
        ];
    }
    
    /**
     * Get price range options (Діапазон цін)
     */
    public static function get_price_range_options() {
        return [
            '' => __('Select Price Range', 'praktik'),
            'up_to_15k' => __('Up to 15k', 'praktik'),
            '16_20k' => __('16-20k', 'praktik'),
            '21_30k' => __('21-30k', 'praktik'),
            '31_40k' => __('31-40k', 'praktik'),
            '41_55k' => __('41-55k', 'praktik'),
            '56_70k' => __('56-70k', 'praktik'),
            '71_100k' => __('71-100k', 'praktik'),
            '101k_plus' => __('101k and above', 'praktik'),
        ];
    }
    
    /**
     * Get courtyard options (Двір) - for houses
     */
    public static function get_courtyard_options() {
        return [
            '' => __('Select Courtyard', 'praktik'),
            'separate' => __('Separate', 'praktik'),
            'shared' => __('Shared', 'praktik'),
        ];
    }
    
    /**
     * Get sewage options (Каналізація) - for houses
     */
    public static function get_sewage_options() {
        return [
            '' => __('Select Sewage', 'praktik'),
            'central' => __('Central', 'praktik'),
            'nearby' => __('Nearby', 'praktik'),
            'pit' => __('Pit', 'praktik'),
            'none' => __('None', 'praktik'),
        ];
    }
    
    /**
     * Get building material options (Матеріал будівництва) - for houses
     */
    public static function get_building_material_options() {
        return [
            '' => __('Select Material', 'praktik'),
            'brick' => __('Brick', 'praktik'),
            'panel' => __('Panel', 'praktik'),
            'monolith' => __('Monolith', 'praktik'),
            'block' => __('Block', 'praktik'),
            'wood_brick' => __('Wood-Brick', 'praktik'),
            'wood' => __('Wood', 'praktik'),
            'gas_block' => __('Gas Block', 'praktik'),
            'timber' => __('Timber', 'praktik'),
            'timber_brick' => __('Timber-Brick', 'praktik'),
            'fin_brick' => __('Fin-Brick', 'praktik'),
            'other' => __('Other', 'praktik'),
        ];
    }
    
    /**
     * Get utility options (Є/Нема/Поряд)
     */
    public static function get_utility_options() {
        return [
            '' => __('Select', 'praktik'),
            'yes' => __('Yes', 'praktik'),
            'nearby' => __('Nearby', 'praktik'),
            'no' => __('No', 'praktik'),
        ];
    }
    
    /**
     * Get source options (Джерело об'єкту)
     */
    public static function get_source_options() {
        return [
            '' => __('Select Source', 'praktik'),
            'olx' => __('OLX.ua', 'praktik'),
            'from_realtor' => __('From Realtor', 'praktik'),
        ];
    }
    
    /**
     * Get shooting status options (Статус зйомки)
     */
    public static function get_shooting_status_options() {
        return [
            '' => __('Select Status', 'praktik'),
            'approved' => __('Approved', 'praktik'),
            'removed' => __('Removed', 'praktik'),
            'published' => __('Published', 'praktik'),
        ];
    }
    
    /**
     * Get cooperation status options (Статус співпраці)
     */
    public static function get_cooperation_status_options() {
        return [
            '' => __('Select Status', 'praktik'),
            'contract_with_us' => __('Contract with Us', 'praktik'),
            'verbal_agreement' => __('Verbal Agreement', 'praktik'),
            'with_all' => __('With All', 'praktik'),
            'competitor_no_contract' => __('Competitor without Contract', 'praktik'),
            'competitor_contract' => __('Competitor Contract', 'praktik'),
            'no_cooperation' => __('No Cooperation', 'praktik'),
        ];
    }
    
    /**
     * Get sales funnel stage options (Етап воронки продажу)
     */
    public static function get_sales_funnel_options() {
        return [
            '' => __('Select Stage', 'praktik'),
            'new_object' => __('New Object', 'praktik'),
            'in_work' => __('In Work', 'praktik'),
            'viewing_approved' => __('Viewing Approved', 'praktik'),
            'viewed' => __('Viewed', 'praktik'),
            'cooperation_approved' => __('Cooperation Approved', 'praktik'),
            'deposit_received' => __('Deposit Received', 'praktik'),
            'sold' => __('Sold', 'praktik'),
        ];
    }
}

