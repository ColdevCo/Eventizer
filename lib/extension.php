<?php

class EventExtention {

	public function __construct()
	{
		$this->load();
	}

	function static scan()
	{
		$exts_path = opendir( __EVENT_EXTENSION_PATH__ );

		$exts = array();
		while ( false !== ( $ext = readdir( $exts_path ) ) ) {
			if( is_dir( $ext ) && $ext != '.' && $ext != '..' ) {
				array_push( $exts , read_info( $ext ) );
			}
		}

		return $exts;
	}

	function load()
	{
		$exts = self::scan();

		foreach ( $exts as $ext ) {
			include_once( $ext[ 'path' ] );
		}
	}

	function static read_info( $extension )
	{
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

	function static parse_info( $comment )
	{
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
}