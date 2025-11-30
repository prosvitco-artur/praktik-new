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
            'Вторинний ринок' => 'Вторинний ринок',
            'Новобудова' => 'Новобудова',
        ];
    }
    
    /**
     * Get house type options (Тип Будинку)
     */
    public static function get_house_type_options() {
        return [
            '' => __('Select House Type', 'praktik'),
            'Будинок' => 'Будинок',
            'Ч/будинку' => 'Ч/будинку',
            'Дача' => 'Дача',
        ];
    }
    
    /**
     * Get location type options (Розташування)
     */
    public static function get_location_type_options() {
        return [
            '' => __('Select Location', 'praktik'),
            'Місто' => 'Місто',
            'Пригород' => 'Пригород',
            'За містом' => 'За містом',
        ];
    }
    
    /**
     * Get condition options (Стан нерухомості)
     */
    public static function get_condition_options() {
        return [
            '' => __('Select Condition', 'praktik'),
            'Авторський проект' => 'Авторський проект',
            'Євроремонт' => 'Євроремонт',
            'Косметичний ремонт' => 'Косметичний ремонт',
            'Житловий стан' => 'Житловий стан',
            'Після будівельників' => 'Після будівельників',
            'Під чистову обробку' => 'Під чистову обробку',
            'Аварійний стан' => 'Аварійний стан',
        ];
    }
    
    /**
     * Get bathroom options (Санвузол)
     */
    public static function get_bathroom_options() {
        return [
            '' => __('Select Bathroom', 'praktik'),
            'Сумісний' => 'Сумісний',
            'Окремий' => 'Окремий',
            'Немає' => 'Немає',
        ];
    }
    
    /**
     * Get heating options (Опалення)
     */
    public static function get_heating_options() {
        return [
            '' => __('Select Heating', 'praktik'),
            'Автономне' => 'Автономне',
            'ТЕЦ' => 'ТЕЦ',
            'Теплові лічильники' => 'Теплові лічильники',
            'Електричне' => 'Електричне',
            'Пічне' => 'Пічне',
            'Відсутнє' => 'Відсутнє',
        ];
    }
    
    /**
     * Get sale status options (Статус продажу)
     */
    public static function get_sale_status_options() {
        return [
            '' => __('Select Sale Status', 'praktik'),
            'Активний продаж' => 'Активний продаж',
            'Продаж відкладено' => 'Продаж відкладено',
            'Знято з продажу' => 'Знято з продажу',
            'Обʼєкт продано' => 'Обʼєкт продано',
        ];
    }
    
    /**
     * Get price range options (Діапазон цін)
     */
    public static function get_price_range_options() {
        return [
            '' => __('Select Price Range', 'praktik'),
            'до 15 тис' => 'до 15 тис',
            '16-20 тис.' => '16-20 тис.',
            '21 - 30 тис.' => '21 - 30 тис.',
            '31 - 40 тис.' => '31 - 40 тис.',
            '41 - 55 тис.' => '41 - 55 тис.',
            '56 - 70 тис.' => '56 - 70 тис.',
            '71 - 100 тис. ' => '71 - 100 тис. ',
            '101 тис і вище' => '101 тис і вище',
        ];
    }
    
    /**
     * Get courtyard options (Двір) - for houses
     */
    public static function get_courtyard_options() {
        return [
            '' => __('Select Courtyard', 'praktik'),
            'Окремий' => 'Окремий',
            'Загальний' => 'Загальний',
        ];
    }
    
    /**
     * Get sewage options (Каналізація) - for houses
     */
    public static function get_sewage_options() {
        return [
            '' => __('Select Sewage', 'praktik'),
            'Ц/каналізація' => 'Ц/каналізація',
            'Поряд' => 'Поряд',
            'Яма' => 'Яма',
            'Відсутня' => 'Відсутня',
        ];
    }
    
    /**
     * Get building material options (Матеріал будівництва) - for houses
     */
    public static function get_building_material_options() {
        return [
            '' => __('Select Material', 'praktik'),
            'Цегла' => 'Цегла',
            'Панель' => 'Панель',
            'Кирпич' => 'Кирпич',
            'Моноліт' => 'Моноліт',
            'Блок' => 'Блок',
            'Дерево-цегла' => 'Дерево-цегла',
            'Дерево' => 'Дерево',
            'Газоблок' => 'Газоблок',
            'Брус' => 'Брус',
            'Брус-Цегла' => 'Брус-Цегла',
            'Фин-кирпич' => 'Фин-кирпич',
            'Інше' => 'Інше',
        ];
    }
    
    /**
     * Get utility options (Є/Нема/Поряд)
     */
    public static function get_utility_options() {
        return [
            '' => __('Select', 'praktik'),
            'Є' => 'Є',
            'Поряд' => 'Поряд',
            'Нема' => 'Нема',
        ];
    }
    
    /**
     * Get source options (Джерело об'єкту)
     */
    public static function get_source_options() {
        return [
            '' => __('Select Source', 'praktik'),
            'olx.ua' => 'olx.ua',
            'від ріелтора' => 'від ріелтора',
        ];
    }
    
    /**
     * Get shooting status options (Статус зйомки)
     */
    public static function get_shooting_status_options() {
        return [
            '' => __('Select Status', 'praktik'),
            'Погоджено' => 'Погоджено',
            'Знято' => 'Знято',
            'Розміщено' => 'Розміщено',
        ];
    }
    
    /**
     * Get cooperation status options (Статус співпраці)
     */
    public static function get_cooperation_status_options() {
        return [
            '' => __('Select Status', 'praktik'),
            'Договір з нами' => 'Договір з нами',
            'Усний договір' => 'Усний договір',
            'З усіма' => 'З усіма',
            'З конкурентом без договору' => 'З конкурентом без договору',
            'Договір з конкурентом' => 'Договір з конкурентом',
            'Не співпрацює' => 'Не співпрацює',
        ];
    }
    
    /**
     * Get sales funnel stage options (Етап воронки продажу)
     */
    public static function get_sales_funnel_options() {
        return [
            '' => __('Select Stage', 'praktik'),
            'Новий об\'єкт' => 'Новий об\'єкт',
            'Взято в роботу' => 'Взято в роботу',
            'Огляд погоджено' => 'Огляд погоджено',
            'Оглянуто' => 'Оглянуто',
            'Співпраця погоджена' => 'Співпраця погоджена',
            'Завдаток отриманий' => 'Завдаток отриманий',
            'Продано' => 'Продано',
        ];
    }
}

