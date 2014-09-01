<?php

function load_extensions() {
	$extensions = scan_extensions();
	$enabled_extensions = unserialize( get_event_options( 'enabled_extensions' ) );
	if ( is_array( $enabled_extensions ) ) {
		foreach ( $extensions as $extension ) {
			if ( in_array( $extension[ 'Extension Name' ] , $enabled_extensions ) ) {
				include_once( $extension[ 'path' ] );
			}
		}
	}
}

function scan_extensions() {
	$exts_path = opendir( __EVENT_EXTENSION_PATH__ );
	$exts = array();
	while ( false !== ( $ext = readdir( $exts_path ) ) ) {
		$ext_path = __EVENT_EXTENSION_PATH__ . $ext;
		if( is_dir( $ext_path ) && $ext != '.' && $ext != '..' ) {
			array_push( $exts , read_info( $ext ) );
		}
	}

	return $exts;
}

function read_info( $extension ) {
	$path 	= __EVENT_EXTENSION_PATH__ . $extension . '/index.php';
	$tokens = token_get_all( file_get_contents( $path ) );

	$comments = array();
	foreach ( $tokens as $token ) {
		if ( $token[0] == T_COMMENT || $token[0] == T_DOC_COMMENT ) {
			$comment = parse_info( $token[1] );
			$comment[ 'path' ] = $path;
			array_push( $comments , $comment );
		}
	}

	return $comments[0];
}

function parse_info( $comment ) {
	$tokens = explode( "\n" , $comment );

	$info = array();
	foreach ( $tokens as $token ) {
		if ( strpos( $token , ':' ) !== true ) {
			$key 	= explode( ':' , $token )[0];
			$value 	= explode( ':' , $token )[1];
			$info[ $key ] = $value;
		}
	}

	return $info;
}

function is_enabled( $extension ) {
    $enabled_extensions = unserialize( get_event_options( 'enabled_extensions' ) );
    return ( is_array( $enabled_extensions ) and in_array( " {$extension}" , $enabled_extensions ) );
}

return load_extensions();