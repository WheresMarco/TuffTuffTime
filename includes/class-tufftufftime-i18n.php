<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://wheresmar.co
 * @since      1.0.0
 *
 * @package    TuffTuffTime
 * @subpackage TuffTuffTime/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    TuffTuffTime
 * @subpackage TuffTuffTime/includes
 * @author     Marco Hyyryläinen <marco@wheresmar.co>
 */
class TuffTuffTime_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'TuffTuffTime',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
