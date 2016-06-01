<?php

/**
 * Plugin Name: Plugin Name
 * Description: Informative short plugin description.
 * Plugin Version: 0.1
 * Author: Author Name
 * Author URI: http://example.com
 */

/**
 * Quickstart:
 *
 * 1. Change Plugin_Name to your unique plugin namespace
 * 2. Update namespace in src/Autoloader.php
 * 3. Create classes in src/ and hook them up here. Plugin_Name\Autoloader 
 *    class expects to be found in src/Plugin_Name/Autoloader.php file.
 */

use Plugin_Name\Autoloader;
//use Plugin_Name\Your_Class;

require_once "src/Autoloader.php";

Autoloader::register();
//Your_Class::yourRules();

//add_action( 'init', function() {
//	Your_Class::doThingsOnInit();
//} );