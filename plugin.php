<?php

/**
 * Plugin Name: Toolbox WordPress Plugin
 * Description: This is a WordPress plugin template, a PSR-* compliant structure and Toolbox consistent libraries to power up any kind of WordPress plugin.
 * Version: 0.2.5
 * Author: Toolbox
 * Author URI: http://itsmikita.com
 */

require "autoload.php";

use Toolbox\Plugin;
use Toolbox\Settings;

/**
 * Example 1:
 *
 * Load plugin i18n
 */
add_action( 'init', function() {
	load_plugin_textdomain( Plugin::TEXTDOMAIN, false, dirname( __FILE__ ) . "/languages" );
} );

/**
 * Example 2:
 *
 * Add admin scripts and styles
 */
add_action( 'admin_enqueue_scripts', function() {
	wp_enqueue_script( 'toolbox', Plugin::getUrl( 'assets/scripts/admin/toolbox.js' ), [ 'jquery' ] );
	wp_enqueue_style( 'toolbox', Plugin::getUrl( 'assets/styles/admin/toolbox.css' ) );
} );

/**
 * Example 3:
 *
 * Add options page
 */
add_action( 'admin_menu', function() {
	$page = "toolbox";
	
	Settings::addPage( [
		'slug' => $page,
		'title' => __( "Toolbox Example Settings", Plugin::TEXTDOMAIN ),
		'menu' => __( "Toolbox", Plugin::TEXTDOMAIN )
	] );
	
	add_action( 'admin_init', function() use( $page ) {
		// "general" tab is added by default
		
		Settings::addSection( [
			'page' => $page,
			'tab' => "general",
			'name' => "foo",
			'title' => __( "Foo", Plugin::TEXTDOMAIN ),
			'description' => __( "Foo settings section description.", Plugin::TEXTDOMAIN ),
		] );
		
		Settings::addField( [
			'page' => $page,
			'tab' => "general",
			'section' => "foo",
			'name' => "toolbox_foo_bar",
			'title' => __( "Bar", Plugin::TEXTDOMAIN )
		] );
		
		Settings::addTab( [
			'page' => $page,
			'name' => "advanced",
			'title' => __( "Advanced", Plugin::TEXTDOMAIN )
		] );
		
		Settings::addSection( [
			'page' => $page,
			'tab' => "advanced",
			'name' => "bar",
			'title' => __( "Bar", Plugin::TEXTDOMAIN ),
			'description' => __( "Bar settings section description.", Plugin::TEXTDOMAIN ),
		] );
		
		Settings::addField( [
			'page' => $page,
			'tab' => "advanced",
			'section' => "bar",
			'name' => "toolbox_bar_foo",
			'title' => __( "Foo", Plugin::TEXTDOMAIN )
		] );
		
	} );
} );
