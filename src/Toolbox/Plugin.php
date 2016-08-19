<?php
namespace Toolbox;

if( ! class_exists( __NAMESPACE__ . "\Plugin" ) ):
class Plugin
{
	/**
	 * Get plugin path
	 *
	 * @param string $path
	 */
	static public function getPath( $path = '' )
	{
		return plugin_dir_path( dirname( __FILE__ ) ) . ltrim( $path, '/' );
	}
	
	/**
	 * Plugin URL
	 *
	 * @param string $path
	 */
	static public function getUrl( $path = '' )
	{
		return plugins_url( $path, dirname( __FILE__ ) );
	}
	
	
}
endif;