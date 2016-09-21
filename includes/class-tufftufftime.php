<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://wheresmar.co
 * @since      1.0.0
 *
 * @package    Tufftufftime
 * @subpackage Tufftufftime/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Tufftufftime
 * @subpackage Tufftufftime/includes
 * @author     Marco Hyyryläinen <marco@wheresmar.co>
 */
class Tufftufftime {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Tufftufftime_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	private $stations;
	private $tufftufftime_options;
  private static $options = array(
    CURLOPT_FRESH_CONNECT   => 1,
    CURLOPT_URL             => "http://api.trafikinfo.trafikverket.se/v1/data.json",
    CURLOPT_RETURNTRANSFER  => 3,
    CURLOPT_POST            => 1,
    CURLOPT_HTTPHEADER      => array('Content-Type: text/xml')
  );

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'tufftufftime';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		$this->tufftufftime_options = get_option('tufftufftime_options');

		// TEMP
		// $this->stations = $this->load_stations();
    // $station_ID = $this->get_station_ID('Stockholm Central');
		//
		// echo "<pre>";
		// var_dump( $this->load_arriving( $station_ID ) );
		// die();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Tufftufftime_Loader. Orchestrates the hooks of the plugin.
	 * - Tufftufftime_i18n. Defines internationalization functionality.
	 * - Tufftufftime_Admin. Defines all hooks for the admin area.
	 * - Tufftufftime_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tufftufftime-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tufftufftime-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-tufftufftime-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-tufftufftime-public.php';

		$this->loader = new Tufftufftime_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Tufftufftime_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Tufftufftime_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Tufftufftime_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'create_options_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Tufftufftime_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Tufftufftime_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
    * Get arriving trains/busses(?) to the station
    *
    * @return array
  	*/
  public function load_arriving( $station_ID ) {
  	$xml = "<REQUEST>" .
          	"<LOGIN authenticationkey='" . $this->tufftufftime_options['tufftufftime_api_key'] . "' />" .
            	"<QUERY objecttype='TrainAnnouncement' orderby='AdvertisedTimeAtLocation'>" .
              "<FILTER>" .
                "<AND>" .
                "<EQ name='ActivityType' value='Ankomst' />" .
                "<EQ name='LocationSignature' value='" . $station_ID . "' />" .
                "<OR>" .
                    "<AND>" .
                      "<GT name='AdvertisedTimeAtLocation' value='\$dateadd(-00:15:00)' />" .
                      "<LT name='AdvertisedTimeAtLocation' value='\$dateadd(14:00:00)' />" .
                    "</AND>" .
                    "<AND>" .
                      "<LT name='AdvertisedTimeAtLocation' value='\$dateadd(00:30:00)' />" .
                      "<GT name='EstimatedTimeAtLocation' value='\$dateadd(-00:15:00)' />" .
                    "</AND>" .
                  "</OR>" .
                "</AND>" .
              "</FILTER>" .
            "</QUERY>" .
          "</REQUEST>";

    // Open up curl session and fire of the request
    $session = curl_init();
    curl_setopt_array($session, self::$options);
    curl_setopt($session, CURLOPT_POSTFIELDS, "$xml");
    $response = curl_exec($session);
    curl_close($session);

    // Check if we got a response
    if(!$response) :
      throw new \Exception("Could not get stations");
		endif;

    return json_decode($response, true);
  }

  /**
    * Get departing trains/busses(?) to the station
    *
    * @return array
    */
  public function load_departing( $station_ID ) {
    $xml = "<REQUEST>" .
            "<LOGIN authenticationkey='" . $this->tufftufftime_options['tufftufftime_api_key'] . "' />" .
            "<QUERY objecttype='TrainAnnouncement' orderby='AdvertisedTimeAtLocation'>" .
              "<FILTER>" .
                "<AND>" .
                "<EQ name='ActivityType' value='Avgang' />" .
                "<EQ name='LocationSignature' value='" . $station_ID . "' />" .
                "<OR>" .
                    "<AND>" .
                      "<GT name='AdvertisedTimeAtLocation' value='\$dateadd(-00:15:00)' />" .
                      "<LT name='AdvertisedTimeAtLocation' value='\$dateadd(14:00:00)' />" .
                    "</AND>" .
                    "<AND>" .
                      "<LT name='AdvertisedTimeAtLocation' value='\$dateadd(00:30:00)' />" .
                      "<GT name='EstimatedTimeAtLocation' value='\$dateadd(-00:15:00)' />" .
                    "</AND>" .
                  "</OR>" .
                "</AND>" .
              "</FILTER>" .
            "</QUERY>" .
            "</REQUEST>";

      // Open up curl session and fire of the request
      $session = curl_init();
      curl_setopt_array( $session, self::$options );
      curl_setopt( $session, CURLOPT_POSTFIELDS, "$xml" );
      $response = curl_exec( $session );
      curl_close( $session );

      // Check if we got a response
      if(!$response) :
        throw new \Exception("Could not get stations");
			endif;

      return json_decode($response, true);
    }

	/**
    * Retrives the stations from the api
    *
		* @since     1.0.0
    * @return json-array
  */
  public function load_stations() {

		// TODO: DON'T USE STATIC API KEY

    $xml = "<REQUEST>" .
              "<LOGIN authenticationkey='" . $this->tufftufftime_options['tufftufftime_api_key'] . "' />" .
              "<QUERY objecttype='TrainStation'>" .
              	"<FILTER/>" .
              	"<INCLUDE>AdvertisedLocationName</INCLUDE>" .
                "<INCLUDE>LocationSignature</INCLUDE>" .
              "</QUERY>" .
            "</REQUEST>";

    // Open up curl session and fire of the request
    $session = curl_init();
    curl_setopt_array( $session, self::$options );
    curl_setopt( $session, CURLOPT_POSTFIELDS, "$xml" );
    $response = curl_exec( $session );
    curl_close( $session );

    // Check if we got a response
    if ( !$response ) :
      throw new \Exception("Could not get stations");
		endif;


    return json_decode( $response, true );
  }

  /**
    * Get the stationID for a station.
    *
    * @param string $name - Name of the station
    * @return string
    */
  private function get_station_ID( $name ) {
    $foundID = "";

    // Loop through the returned array to find the id
    foreach( $this->stations['RESPONSE']['RESULT']['0']['TrainStation'] as $station ) :
      if ( array_search($name, $station) ) :
        $foundID = $station['LocationSignature'];
        break;
      endif;
    endforeach;

    // No match? Throw a exception
    if ($foundID === "") :
      throw new \Exception("Could not find ID in returned array. Must be name exact name (ex. Stockholm C)");
		endif;

    return $foundID;
  }

  /**
    * Get the name of a station from id.
    *
    * @param string $id - ID of the station
    * @return string
    */
  public function get_station_name( $id ) {
    $foundName = "";

    // // Loop through the returned array to find the name
    foreach( $this->stations['RESPONSE']['RESULT']['0']['TrainStation'] as $station ) :
      if (array_search($id, $station)) :
        $foundName = $station['AdvertisedLocationName'];
      endif;
    endforeach;

    // No match? Throw a exception
    if ($foundName === "") :
      throw new \Exception("Could not find the name in returned array.");
		endif;

    return $foundName;
  }

}
