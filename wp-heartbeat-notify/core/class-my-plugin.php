<?php

/**
 * My_Plugin_Main_Class
 */
class My_Plugin {

		
	var 

		// Domain of the plugin (from filename)
		$domain,

		// Required software version
		$required = array(
			'wordpress'	=>	'3.0',
			'php'		=>	'5.0.0'
		),

		// Plugin uri for linking
		$uri,

		// Plugin folder
		$dir,

		// Plugin version option name
		$version_option,

		// This will store plugin file location
		$file,

		// If version not set
		$version = '0.0.1';

	function __construct( $file , array $args = array() ) {

		// Get the plugin main file
		$this->file = $file;

		// Get the plugin uri
		$this->uri = plugin_dir_url( $file );

		// Get the plugin dir
		$this->dir = plugin_dir_path( $file );

		// Set the domain and textdomain based on class name
		$this->textdomain = $this->domain = basename( $file, '.php' );

		// Set required software versions
		if ( isset( $args['required'] ) )
			$this->required = array_merge( $this->required, $args['required'] );

		// Plugin data to retrieve
		$plugin_data_fields = array(
			'name' 			=> 'Plugin Name',
			'pluginuri' 	=> 'Plugin URI',
			'version' 		=> 'Version',
			'description' 	=> 'Description',
			'author' 		=> 'Author',
			'authoruri' 	=> 'Author URI',
			'textdomain' 	=> 'Text Domain'
		);

		// Retrieve current plugin version
		$plugin_data = get_file_data( $this->file, $plugin_data_fields );

		foreach ( $plugin_data_fields as $key => $value ) {
			if ( isset( $plugin_data[ $key ] ) && !empty( $plugin_data[ $key ] ) ) {

				$this->{$key} = $plugin_data[ $key ];

			}
		}

		// Set the name of version option
		$this->version_option = $this->domain . '_version';

		// Register activation hook
		register_activation_hook( $this->file, array( $this, 'plugin_activation' ) );

		// Localize the plugin
		add_action( 'plugins_loaded', array( $this, 'plugin_init' ) );

		// On install
		add_action( 'init', array( $this, 'plugin_install' ) );

		// On update
		add_action( 'init', array( $this, 'plugin_update' ) );

	}

	/**
	 * Check for software version compatibility
	 */
	function plugin_activation() {

		// Check WP Version
		if ( version_compare( get_bloginfo('version'), $this->required['wordpress'], '<') )
			$error = sprintf( __( 'This plugin requires WordPress %s or greater.', $this->domain ), $this->required['wordpress'] );
		
		// Check PHP Version
		if ( version_compare( PHP_VERSION, $this->required['php'], '<' ) )
			$error = sprintf( __( 'This plugin requires PHP %s or greater.', $this->domain ), $this->required['php'] );

		if ( isset( $error ) ){

			// Deactivate plugin and DIE!!!
			deactivate_plugins( basename( $this->file ) );
			wp_die( $error );

		}

	}

	/**
	 * Localize the plugin
	 */
	function plugin_init() {

		load_plugin_textdomain( $this->textdomain, false, dirname( plugin_basename( $this->file ) ) . '/langs/' );

	}

	/**
	 * Plugin update
	 *
	 * @uses do_action()
	 */
	function plugin_update() {

		// if version is already set and new version is higher than current
		if ( false !== get_option( $this->version_option ) 
			 && version_compare( $this->version, get_option( $this->version_option ), '>' ) ) {

			update_option( $this->version_option, $this->version );
			do_action( $this->domain . '_update' );

		}

	}

	/**
	 * First plugin install
	 *
	 * @uses do_action()
	 */
	function plugin_install() {

		// If version in not set
		if ( false === get_option( $this->version_option ) ) {

			do_action( $this->domain . '_install' );
			update_option( $this->version_option, $this->version );

		}
			
	}

} // Class My_Plugin
