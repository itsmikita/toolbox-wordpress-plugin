<?php

namespace Plugin_Name;

class Autoloader {
	/**
	 * Setup class autoloader
	 */
	static public function register() {
		/**
		 * Autoload classes
		 *
		 * @param $path
		 */
		spl_autoload_register( function( $path ) {
			if( 0 !== strpos( $path, __NAMESPACE__ ) )
				return;
			
			$path = str_replace( "\\", "/", $path );
			$path = preg_replace( '/^' . str_replace( '\\', '\/', __NAMESPACE__ ) . '\//', '', $path );
			$file = plugin_dir_path( dirname( __FILE__ ) ) . "src/{$path}.php";
			
			if( ! file_exists( $file ) )
				return;
			
			require_once $file;
		} );
	}
}