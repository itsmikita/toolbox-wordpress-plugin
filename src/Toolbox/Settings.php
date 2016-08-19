<?php
namespace Toolbox;

use Toolbox\Prototypes\Singleton;

if( ! class_exists( __NAMESPACE__ . "\Settings" ) ):
class Settings extends Singleton
{
	/**
	 * Add options page
	 *
	 * @param array $page
	 */
	public static function addPage( $page )
	{
		$settings = self::getInstance();
		$defaults = [
			'slug' => "",
			'title' => "",
			'menu' => "",
			'tabs' => []
		];
		
		$page = array_merge( $defaults, $page );
		
		if( ! isset( $settings->pages ) )
			$settings->pages = [];
		
		$settings->pages[ $page['slug'] ] = $page;
		
		$hook = add_options_page( 
			$page['title'], 
			$page['menu'], 
			'manage_options',
			$page['slug'],
			[ __CLASS__, "displayPage" ]
		);
		
		return $hook;
	}
	
	/**
	 * Add settings page tab
	 *
	 * @param array $tab
	 */
	public static function addTab( $tab )
	{
		$settings = self::getInstance();
		$defaults = [
			'page' => "",
			'name' => "general",
			'title' => __( "General", Config::TEXTDOMAIN ),
			'sections' => [],
			'saveButton' => true,
			'class' => ""
		];
		$tab = array_merge( $defaults, $tab );
		
		if( ! isset( $settings->pages ) || ! isset( $tab['page'] ) )
			return false;
		
		$settings->pages[ $tab['page'] ]
			['tabs'][ $tab['name'] ] = $tab;
	}
	
	/**
	 * Add settings section
	 *
	 * @param array $section
	 */
	public static function addSection( $section )
	{
		$settings = self::getInstance();
		$defaults = [
			'page' => null,
			'tab' => "general",
			'name' => "",
			'title' => "",
			'description' => "",
			'fields' => []
		];
		$section = array_merge( $defaults, $section );
		
		if( ! isset( $settings->pages ) || ! isset( $section['page'] ) )
			return false;
		
		if( ! isset( $settings->pages[ $section['page'] ]['tabs'][ $section['tab'] ] ) ) {
			$section['tab'] = "general";
			
			self::addTab( [ 'page' => $section['page'] ] );
		}
		
		$settings->pages[ $section['page'] ]
			['tabs'][ $section['tab'] ]
			['sections'][ $section['name'] ] = $section;
		
		add_settings_section(
			$section['name'], 
			$section['title'], 
			[ __CLASS__, "printSectionDescription" ], 
			$section['page']
		);
	}
	
	public static function printSectionDescription() {}
	
	/**
	 * Add settings field
	 *
	 * @param array $setting
	 */
	public static function addField( $setting )
	{
		$settings = self::getInstance();
		$defaults = [
			'page' => null,
			'tab' => "general",
			'section' => "",
			'name' => null,
			'title' => "",
			'type' => "text",
			'class' => "regular-text",
			'default' => null
		];
		$setting = array_merge( $defaults, $setting );
		
		if( ! isset( $settings->pages ) || ! isset( $setting['page'] ) )
			return false;
		
		if( $setting['name'] )
			register_setting( $setting['page'], $setting['name'] );
		
		if( ! empty( $setting['options'] ) )
			foreach( $setting['options'] as $option )
				if( ! empty( $option['name'] ) )
					register_setting( $setting['page'], $option['name'] );
		
		$settings->pages[ $setting['page'] ]
			['tabs'][ $setting['tab'] ]
			['sections'][ $setting['section'] ]
			['fields'][] = $setting;
		
		if( isset( $setting['default'] ) && ! Config::get( $setting['name'] ) )
			Config::set( $setting['name'], $setting['default'] );
	}
	
	/**
	 * Display settings page
	 */
	public static function displayPage() 
	{
		$settings = self::getInstance();
		
		if( ! isset( $_REQUEST['page'] ) || empty( $settings->pages[ $_REQUEST['page'] ] ) )
			return;
		
		if( empty( $_REQUEST['tab'] ) )
			$_REQUEST['tab'] = "general";
		
		$title = $settings->pages[ $_REQUEST['page'] ]['title'];
		$currentTab = $settings->pages[ $_REQUEST['page'] ]['tabs'][ $_REQUEST['tab'] ];
		$saveButton = $currentTab['saveButton'];
		
		if( 1 < count( $settings->pages[ $_REQUEST['page'] ]['tabs'] ) ) {
			$hasTabs = true;
			$tabs = array_map( function( $tab ) use( $currentTab ) {
				$tab['selected'] = ( $tab['name'] == $currentTab['name'] );
				unset( $tab['sections'] );
				
				return compact( 'tab' );
			}, array_values( $settings->pages[ $_REQUEST['page'] ]['tabs'] ) );
		}
		else
			$tabs = false;
		
		$sections = array_map( function( $section ) {
			$section['fields'] = array_map( function( $field ) {
				if( ! empty( $field['name'] ) )
					$field['value'] = Config::get( $field['name'] );
				
				if( ! empty( $field['options'] ) )
					$field['options'] = array_map( function( $option ) use( $field ) {
						if( isset( $option['name'] ) )
							$option['checked'] = ( 1 == Config::get( $option['name'] ) );
						elseif( isset( $field['value'] ) )
							$option['selected'] =
							$option['checked'] = ( $option['value'] == $field['value'] );
						
						return compact( 'option' );
					}, $field['options'] );
				
				if( "checkbox" == $field['type'] )
					$field['checked'] = ( 1 == Config::get( $field['name'] ) );
				
				if( empty( $field[ $field['type'] ] ) )
					$field[ $field['type'] ] = true;
				
				if( "table" == $field['type'] && is_callable( $field['table']['table']['rows'] ) )
					$field['table']['table']['rows'] = call_user_func( $field['table']['table']['rows'] );
				
				return compact( 'field' );
			}, $section['fields'] );
			
			return compact( 'section' );
		}, array_values( $currentTab['sections'] ) );
		
		ob_start();
		settings_fields( $_REQUEST['page'] );
		
		$hidden = ob_get_clean();
		$page = compact( 'title', 'hasTabs', 'tabs', 'hidden', 'sections', 'saveButton' );
		$mustache = Plugin::getMustache();
		
		print $mustache->render( 'admin/settings', $page );
	}
	
	/**
	 * FIX: Do not update options that are not visible on current page/tab
	 *
	 * @param string $value
	 * @param string $option
	 * @param string $oldValue
	 */
	public static function preUpdateOption( $value, $option, $oldValue )
	{
		if( empty( $_REQUEST['option_page'] ) )
			return $value;
		
		$settings = self::getInstance();
		$pages = array_keys( $settings->pages );
		$name = $_REQUEST['option_page'];
		
		if( ! in_array( $name, $pages ) )
			return $value;
		
		$page = $settings->pages[ $name ];
		$options = [];
		$currentTab = "general";
		
		if( ! empty( $_REQUEST['tab'] ) )
			$currentTab = $_REQUEST['tab'];
		
		foreach( $page['tabs'] as $tab ) {
			foreach( $tab['sections'] as $section ) {
				foreach( $section['fields'] as $field ) {
					if( ! empty( $field['options'] ) ) {
						foreach( $field['options'] as $fieldOption ) {
							if( $fieldOption['name'] == $option )
								$optionTab = $tab['name'];
							
							$options[] = $fieldOption['name'];
						}
					}
					elseif( ! empty( $field['name'] ) ) {
						$options[] = $field['name'];
						
						if( $field['name'] == $option )
							$optionTab = $tab['name'];
					}
				}
			}
		}
		
		if( in_array( $option, $options ) && empty( $_REQUEST[ $option ] ) && $currentTab != $optionTab )
			$value = get_option( $option );
		
		return $value;
	}
}
endif;