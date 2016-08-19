<?php
namespace Toolbox\Prototypes;

if( ! class_exists( __NAMESPACE__ . "\Singleton" ) ):
class Singleton
{
	/**
	 * Protect constructor
	 */
	protected function __construct() {}
	
	/**
	 * Prevent cloning of the instance
	 */
	private function __clone() {}
	
	/**
	 * Prevent unserializing the instance
	 */
	private function __wakeup() {}
	
	/**
	 * Get instance
	 *
	 * Supports extends (Or not really?)
	 */
	public static function get()
	{
		static $instances = [];
		
		$class = get_called_class();
		
		if( ! isset( $instances[ $class ] ) )
			$instances[ $class ] = new $class();
		
		return $instances[ $class ];
	}
}
endif;