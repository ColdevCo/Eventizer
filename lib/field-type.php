<?php

function text( $name, $args = array() ) {
	global $post;

	$label = isset( $args['label'] ) ? $args['label'] : $name;
	$style = isset( $args['style'] ) ? $args['style'] : 'width: 100%;';

	$meta_value = get_post_meta( $post->ID, $name, true );

	$html = "<label for=\"{$name}\">{$label}</label>";
	$html .= "<input
        style=\"{$style}\"
        id=\"{$name}\"
        type=\"text\"
        name=\"{$name}\"
        value=\"{$meta_value}\" />
        <br />";

	return $html;
}

function textarea( $name, $args ) {
	global $post;

	$label = isset( $args['label'] ) ? $args['label'] : $name;
	$row   = isset( $args['row'] ) ? $args['row'] : 3;
	$cols  = isset( $args['cols'] ) ? $args['cols'] : 2;
	$style = isset( $args['style'] ) ? $args['style'] : 'width: 100%;';

	$meta_value = get_post_meta( $post->ID, $name, true );

	$html = "<label for=\"{$name}\">{$label}</label>";
	$html .= "<textarea
        rows=\"{$row}\"
        cols=\"{$cols}\"
        style=\"{$style}\"
        id=\"{$name}\"
        name=\"{$name}\">{$meta_value}</textarea>
        <br />";

	return $html;
}

function radio( $name, $args ) {

}

function checkbox( $name, $args ) {

}

function datepicker( $name, $args ) {

}

function color( $name, $args ) {

}