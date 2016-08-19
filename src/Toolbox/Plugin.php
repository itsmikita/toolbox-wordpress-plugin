<?php
namespace Toolbox;

if( ! class_exists( __NAMESPACE__ . "\Plugin" ) ):
class Plugin
{
	// Textdomain
	const TEXTDOMAIN = "toolbox-wordpress-plugin";
	
	/**
	 * Get plugin path
	 *
	 * @param string $path
	 */
	static public function getPath( $path = '' )
	{
		return plugin_dir_path( dirname( dirname( __FILE__ ) ) ) . ltrim( $path, '/' );
	}
	
	/**
	 * Plugin URL
	 *
	 * @param string $path
	 */
	static public function getUrl( $path = '' )
	{
		return plugins_url( $path, dirname( dirname( __FILE__ ) ) );
	}
	
	/**
	 * Get Mustache instance (Singleton)
	 */
	public static function getMustache() {
		static $mustache = null;
		
		if( empty( $mustache ) ) {
			if( ! class_exists( '\Mustache_Autoloader' ) ) {
				require_once self::getPath( '/vendor/mustache-php/src/Mustache/Autoloader.php' );
				\Mustache_Autoloader::register();
			}
			
			$mustache = new \Mustache_Engine( [
				'loader' => new \Mustache_Loader_FilesystemLoader( self::getPath( '/assets/templates' ), [
					'extension' => "ms"
				] ),
				'partials_loader' => new \Mustache_Loader_FilesystemLoader( self::getPath( '/assets/templates/snippets' ), [
					'extension' => "ms"
				] ),
				'cache' => WP_CONTENT_DIR . '/cache/mustache',
				'helpers' => self::getMustacheHelpers()
			] );
		}
		
		return $mustache;
	}
	
	/**
	 * Get Mustache helpers
	 */
	public static function getMustacheHelpers() {
		return [
			/**
			 * i18n
			 *
			 * @param string $text
			 * @param string $helper
			 */
			'i18n' => function( $text, $helper ) {
				return __( $helper->render( $text ), self::TEXTDOMAIN );
			},
		];
	}
}
endif;