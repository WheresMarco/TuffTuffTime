<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wheresmar.co
 * @since      1.0.0
 *
 * @package    Tufftufftime
 * @subpackage Tufftufftime/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tufftufftime
 * @subpackage Tufftufftime/public
 * @author     Marco Hyyryläinen <marco@wheresmar.co>
 */
class Tufftufftime_Public extends Tufftufftime {

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
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tufftufftime_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tufftufftime_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tufftufftime-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tufftufftime_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tufftufftime_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tufftufftime-public.js', array( 'jquery' ), $this->version, false );

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

    $tufftufftime_options = get_option('tufftufftime_options');
		$stations = $this->load_stations( $tufftufftime_options );
    $station_ID = $this->get_station_ID( $tufftufftime_options, $stations, 'Stockholm Central');

    if ( $attributes['type'] === 'arriving' ) :
		  $data = $this->load_arriving( $tufftufftime_options, $station_ID );
    else :
      $data = $this->load_departing( $tufftufftime_options, $station_ID );
    endif;

		return include( plugin_dir_path( __FILE__ ) . 'partials/tufftufftime-public-simple-timetable.php' );

	}

}
