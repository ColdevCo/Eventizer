<?php

class HTML
{

    private static function escape_html( $value )
    {
        return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
    }

    public static function attributes( $attributes )
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

    public static function safe_string( $string )
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

    public static function hidden( $name, $value, $_attributes = array() )
    {
        unset($_attributes['value']);
        $attributes = self::attributes( $_attributes );

        $html = "<input type='hidden' name='{$name}' {$attributes} value='{$value}' />";
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
        $value = array_key_exists( 'value', $_attributes ) ? $_attributes['value'] : '';
        unset($_attributes['value']);

        $attributes = self::attributes( $_attributes );

        $html = "<textarea name='{$name}' {$attributes}>{$value}</textarea>";
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

    public static function checkbox( $text, $name, $_attributes = array() )
    {
        $value = array_key_exists( 'value', $_attributes ) ? $_attributes['value'] : self::safe_string( $text );
        $checked = array_key_exists( 'checked', $_attributes ) ? 'checked=' . $_attributes['checked'] : '';

        unset($_attributes['value']);
        unset($_attributes['checked']);

        $attributes = self::attributes( $_attributes );

        $html = "<label {$attributes}><input type='checkbox' name='{$name}' value='{$value}' {$checked} />{$text}</label>";
        return $html;
    }

    public static function dropdown( $name, $options = array(), $_attributes = array() )
    {
        $selected = array_key_exists( 'value', $_attributes ) ? $_attributes['value'] : '';
        unset($_attributes['value']);

        $attributes = self::attributes( $_attributes );

        $html = "<select name='{$name}' {$attributes}>";
        foreach ( (array) $options as $key => $value ) {

            if( is_numeric($key) && ! is_array($value) ) $key = strtolower($value);

            if ( is_array($value) ) {
                $html .= "<optgroup label='{$key}'>";
                foreach ( $value as $k => $v ) {
                    $html .= "<option value='{$k}' " . (($k == $selected) ? 'selected' : '') . ">{$v}</option>";
                }
                $html .= "</optgroup>";
            } else {
                $html .= "<option value='{$key}' " . (($key == $selected) ? 'selected' : '') . ">{$value}</option>";
            }
        }
        $html .= "</select>";

        return $html;
    }

    public static function button( $name, $_attributes = array() )
    {
        $attributes = self::attributes( $_attributes );

        $html = "<button {$attributes}>{$name}</button>";
        return $html;
    }

    public static function datepicker( $name, $options = array() )
    {
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_style( 'ev-jquery-style', plugins_url( '', dirname( __FILE__ ) ) . '/assets/lib/Aristo/Aristo.css' );

        if( array_key_exists( 'class', $options ) ) {
            $options['class'] = 'datepicker ' . $options['class'];
        } else {
            $options['class'] = 'datepicker';
        }

        $html = "";
        $html .= self::text( $name, $options );

        return $html;
    }

    public static function timepicker( $name, $options = array() )
    {
        $value = array_key_exists( 'value', $options ) ? $options['value'] : array();
        unset($options['value']);

        $attributes = self::attributes( $options );

        $hours = array();
        for ($i = 1; $i <= 12; $hours[$i] = sprintf("%02s", $i), $i++);

        $minutes = array();
        for ($i = 0; $i <= 59; $minutes[$i] = sprintf("%02s", $i), $i++);

        $html = "<div {$attributes}>";
        $html .= self::dropdown( $name . '-hour', $hours, array('value' => $value['hour']) );
        $html .= "&nbsp; : &nbsp;";
        $html .= self::dropdown( $name . '-minute', $minutes, array('value' => $value['minute']) );
        $html .= self::dropdown( $name . '-meridiem', array('AM', 'PM'), array('value' => $value['meridiem']) );
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
        $value = array_key_exists( 'value', $_attributes ) ? $_attributes['value'] : array();
        unset($_attributes['value']);

        $attributes = self::attributes( $_attributes );

        $html = "";
        $html .= "<div id='map-{$name}' {$attributes}></div>";
        $html .= self::hidden( $name . '-lat', $value['lat'] );
        $html .= self::hidden( $name . '-lng', $value['lng'] );

        return $html;
    }
}

class Form
{

    public $post_id;

    function __construct( $post_id = NULL )
    {
        $this->post_id = $post_id;
    }

    public function open( $action, $_attributes = array() )
    {
        $method = array_key_exists( 'method', $_attributes ) ? $_attributes['method'] : 'post';
        return "<form action='{$action}' method='{$method}'>";
    }

    public function close()
    {
        return "</form>";
    }

    public function text( $name, $_attributes = array() )
    {
        $value = get_post_meta( $this->post_id, $name, true );

        if( empty($value) )
            $value = array_key_exists( 'value', $_attributes ) ? $_attributes['value'] : '';

        if( ! empty($value) )
            $_attributes['value'] = $value;

        $name = $this->post_id . "[{$name}]";

        return HTML::text( $name, $_attributes);
    }

    public function textarea( $name, $_attributes = array() )
    {
        $value = get_post_meta( $this->post_id, $name, true );

        if( empty($value) )
            $value = array_key_exists( 'value', $_attributes ) ? $_attributes['value'] : '';

        if( ! empty($value) )
            $_attributes['value'] = $value;

        $name = $this->post_id . "[{$name}]";

        return HTML::textarea( $name, $_attributes);
    }

    public function radio( $text, $name, $_attributes = array() )
    {
        $value = array_key_exists( 'value', $_attributes ) ? $_attributes['value'] : HTML::safe_string($text);

        $checked = get_post_meta( $this->post_id, $name, true );
        if( ! empty($checked) )
            $_attributes['checked'] = true;

        if( ! empty($value) && ! empty($checked) && $value !== $checked )
            unset( $_attributes['checked'] );

        $name = $this->post_id . "[{$name}]";

        return HTML::radio( $text, $name, $_attributes);
    }

    public function checkbox( $text, $name, $_attributes = array() )
    {
        $value = HTML::safe_string($text);

        $checked = get_post_meta( $this->post_id, $name, true );
        if( ! empty($checked) )
            $_attributes['checked'] = true;

        if( ! empty($value) && ! empty($checked) && $value !== $checked )
            unset( $_attributes['checked'] );

        $name = $this->post_id . "[{$name}]";

        return HTML::checkbox( $text, $name, $_attributes);
    }

    public function datepicker( $name, $_options = array() )
    {
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_style( 'ev-jquery-style', plugins_url( '', dirname( __FILE__ ) ) . '/assets/lib/Aristo/Aristo.css' );

        if( array_key_exists( 'class', $_options ) ) {
            $_options['class'] = 'datepicker ' . $_options['class'];
        } else {
            $_options['class'] = 'datepicker';
        }

        $value = get_post_meta( $this->post_id, $name, true );

        if( empty($value) )
            $value = array_key_exists( 'value', $_options ) ? $_options['value'] : '';

        if( ! empty($value) )
            $_options['value'] = date('F j, Y', strtotime($value));

        $name = $this->post_id . "[{$name}]";

        return HTML::text( $name, $_options );
    }

    public function timepicker( $name, $_options = array() )
    {
        $format = array_key_exists( 'format', $_options ) ? $_options['format'] : 12;
        unset( $_options['format'] );

        $hours = array();
        for ($i = 1; $i <= $format; $hours[sprintf("%02s", $i)] = sprintf("%02s", $format === 12 ? $i : $i - 1), $i++);

        $minutes = array();
        for ($i = 0; $i <= 59; $minutes[sprintf("%02s", $i)] = sprintf("%02s", $i), $i++);

        $value = array_key_exists( 'value', $_options ) ? $_options['value'] : array();
        unset( $_options['value'] );

        if( ! is_array($value) || empty($value) ) {
            $value = array('hour' => 0, 'minute' => 0, 'meridiem' => '');
        }

        $time = get_post_meta( $this->post_id, $name, true );
        preg_match('/(?<hour>\d+):(?<minute>\d+) (?<meridiem>\w+)/', $time, $matches);

        if( ! empty($matches) ) {
            $value = array('hour' => $matches['hour'], 'minute' => $matches['minute'], 'meridiem' => $matches['meridiem']);
        }

        $attributes = HTML::attributes( $_options );

        $hour = HTML::dropdown( $this->post_id . "[{$name}-hour]", $hours, array( 'value' => $value['hour'] ) );
        $minute = HTML::dropdown( $this->post_id . "[{$name}-minute]", $minutes, array( 'value' => $value['minute'] ) );
        $meridiem = HTML::dropdown( $this->post_id . "[{$name}-meridiem]", array('AM', 'PM'), array( 'value' => $value['meridiem'] ) );

        if( $format === 12 )
            return "<div {$attributes}>{$hour}&nbsp; : &nbsp;{$minute} {$meridiem}</div>";
        else
            return "<div {$attributes}>{$hour}&nbsp; : &nbsp;{$minute}</div>";
    }

    public function geoinput( $name, $_attributes = array() )
    {
        $value = array_key_exists( 'value', $_attributes ) ? $_attributes['value'] : array();
        unset($_attributes['value']);

        if( ! is_array($value) || empty($value) ) {
            $value = array('lat' => '', 'lng' => '');
        }

        $latLng = get_post_meta( $this->post_id, $name, true );
        if( $latLng )
        {
            $lat = explode( ',', $latLng )[0];
            $lng = explode( ',', $latLng )[1];
            $value = array('lat' => $lat, 'lng' => $lng);
        }

        $attributes = HTML::attributes( $_attributes );

        $html = "";
        $html .= "<div id='{$name}' {$attributes}></div>";
        $html .= HTML::hidden( $this->post_id . "[{$name}-lat]" , $value['lat'], array('id' => "{$name}-lat") );
        $html .= HTML::hidden( $this->post_id . "[{$name}-lng]", $value['lng'], array('id' => "{$name}-lng") );

        return $html;
    }
}