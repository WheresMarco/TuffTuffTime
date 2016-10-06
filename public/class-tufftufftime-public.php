<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wheresmar.co
 * @since      1.0.0
 *
 * @package    TuffTuffTime
 * @subpackage TuffTuffTime/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    TuffTuffTime
 * @subpackage TuffTuffTime/public
 * @author     Marco Hyyryläinen <marco@wheresmar.co>
 */
class TuffTuffTime_Public extends TuffTuffTime {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of this plugin.
	 */
	protected $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

  /**
	 * Display the shortcode.
   * Ex: [tufftufftime station="Stockholm Central" limit="5" type="arriving"]
	 *
	 * @since    1.0.0
	 */
	public function display_simple_timetable( $attributes ) {

    // Define the array of defaults
		$defaults = array(
			'station' => 'Stockholm Central',
      'limit' => '5',
      'type' => 'arriving'
		);

		// And merge them together
		$attributes = wp_parse_args( $attributes, $defaults );

    $TuffTuffTime_options = get_option('TuffTuffTime_options');
		$stations = $this->load_stations( $TuffTuffTime_options );
    $station_ID = $this->get_station_ID( $TuffTuffTime_options, $stations, 'Stockholm Central');

    if ( $attributes['type'] === 'arriving' ) :
		  $data = $this->load_arriving( $TuffTuffTime_options, $station_ID );
    else :
      $data = $this->load_departing( $TuffTuffTime_options, $station_ID );
    endif;

		return include( plugin_dir_path( __FILE__ ) . 'partials/TuffTuffTime-public-simple-timetable.php' );

	}

}
