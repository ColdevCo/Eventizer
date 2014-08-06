<?php

class HTML
{

    private static function escape_html( $value )
    {
        return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
    }

    private static function attributes( $attributes )
    {
        $attr = array();

        foreach ( (array) $attributes as $key => $value )
        {
            if ( is_numeric($key) ) $key = $value;

            if ( ! is_null($value)) {
                array_push( $attr, "{$key}='" . self::escape_html($value) . "'" );
            }

        }

        return count($attr) > 0 ? ' '.implode(' ', $attr) : '';
    }

    private static function safe_string( $string )
    {
        $string = str_replace( " ", "-", $string );
        return strtolower( $string );
    }

    public static function label( $text, $name, $_attributes = array() )
    {
        $attributes = self::attributes( $_attributes );

        $html = "<label for='{$name}' {$attributes}>{$text}</label>";
        return $html;
    }

    public static function hidden( $name, $value )
    {
        $html = "<input type='hidden' name='{$name}' value='{$value}' />";
        return $html;
    }

    public static function text( $name, $_attributes = array() )
    {
        $attributes = self::attributes( $_attributes );

        $html = "<input type='text' name='{$name}' {$attributes} />";
        return $html;
    }

    public static function textarea( $name, $_attributes = array() )
    {
        $attributes = self::attributes( $_attributes );

        $html = "<textarea name='{$name}' {$attributes}></textarea>";
        return $html;
    }

    public static function radio( $text, $name, $_attributes = array() )
    {
        $value = array_key_exists( 'value', $_attributes ) ? $_attributes['value'] : self::safe_string( $text );
        $checked = array_key_exists( 'checked', $_attributes ) ? 'checked=' . $_attributes['checked'] : '';

        unset($_attributes['value']);
        unset($_attributes['checked']);

        $attributes = self::attributes( $_attributes );

        $html = "<label {$attributes}><input type='radio' name='{$name}' value='{$value}' {$checked} />{$text}</label>";
        return $html;
    }

    public static function dropdown( $name, $options = array(), $_attributes = array() )
    {
        $attributes = self::attributes( $_attributes );

        $html = "<select name='{$name}' {$attributes}>";
        foreach ( (array) $options as $key => $value ) {

            if( is_numeric($key) && ! is_array($value) ) $key = strtolower($value);

            if ( is_array($value) ) {
                $html .= "<optgroup label='{$key}'>";
                foreach ( $value as $k => $v ) {
                    $html .= "<option value='{$k}'>{$v}</option>";
                }
                $html .= "</optgroup>";
            } else {
                $html .= "<option value='{$key}'>{$value}</option>";
            }
        }
        $html .= "</select>";

        return $html;
    }

    public static function datepicker( $name, $options = array() )
    {
        wp_enqueue_script( 'jquery-ui-datepicker' );

        if( array_key_exists( 'class', $options ) ) {
            $options['class'] = 'datepicker ' . $options['class'];
        } else {
            $options['class'] = 'datepicker';
        }

        $html = "";
        $html .= self::text( $name . '-date', $options );

        return $html;
    }

    public static function timepicker( $name, $options = array() )
    {
        $attributes = self::attributes( $options );

        $hours = array();
        for ($i = 1; $i <= 12; $hours[$i] = sprintf("%02s", $i), $i++);

        $minutes = array();
        for ($i = 0; $i <= 59; $minutes[$i] = sprintf("%02s", $i), $i++);

        $html = "<div {$attributes}>";
        $html .= self::dropdown( $name . '-hour', $hours );
        $html .= "&nbsp; : &nbsp;";
        $html .= self::dropdown( $name . '-minute', $minutes );
        $html .= self::dropdown( $name . '-ampm', array('AM', 'PM') );
        $html .= "</div>";

        return $html;
    }

    public static function datetimepicker( $name, $options = array() )
    {
        $html = "";
        $html .= self::datepicker( $name, $options );
        $html .= self::timepicker( $name, $options );

        return $html;
    }

    public static function geoinput( $name, $_attributes = array() )
    {
        $attributes = self::attributes( $_attributes );

        $html = "";
        $html .= "<div id='map-{$name}' {$attributes}></div>";
        $html .= self::hidden( $name . '-lat', '' );
        $html .= self::hidden( $name . '-lng', '' );

        return $html;
    }
}