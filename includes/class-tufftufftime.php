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
 * @package    TuffTuffTime
 * @subpackage TuffTuffTime/includes
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
 * @package    TuffTuffTime
 * @subpackage TuffTuffTime/includes
 * @author     Marco Hyyryläinen <marco@wheresmar.co>
 */
class TuffTuffTime {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      TuffTuffTime_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

  /**
	 * A store for all the stations retrived from API.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      array    $stations    The stations.
	 */
	private $stations;

  /**
	 * Static settings for the CURL.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      array    $version    Settings for CURL.
	 */
  private static $curl_options = array(
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
	 * @since    2.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'TuffTuffTime';
		$this->version = '2.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - TuffTuffTime_Loader. Orchestrates the hooks of the plugin.
	 * - TuffTuffTime_i18n. Defines internationalization functionality.
	 * - TuffTuffTime_Admin. Defines all hooks for the admin area.
	 * - TuffTuffTime_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-TuffTuffTime-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-TuffTuffTime-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-TuffTuffTime-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-TuffTuffTime-public.php';

		$this->loader = new TuffTuffTime_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the TuffTuffTime_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new TuffTuffTime_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new TuffTuffTime_Admin( $this->get_plugin_name(), $this->get_version() );

    $this->loader->add_action( 'admin_menu', $plugin_admin, 'create_options_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new TuffTuffTime_Public( $this->get_plugin_name(), $this->get_version() );

    add_shortcode( 'tufftufftime', array( $plugin_public, 'display_simple_timetable' ) );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    2.0.0
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
	 * @return    TuffTuffTime_Loader    Orchestrates the hooks of the plugin.
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
   * Get arriving trains to the station
   *
   * @since     1.0.0
   * @return    array
   */
  public function load_arriving( $TuffTuffTime_options, $station_ID ) {

    $transient_id = '_TuffTuffTime_arriving_' . $station_ID;

    if ( false === ( $arriving = get_transient( $transient_id ) ) ) :

      $xml = "<REQUEST>" .
            	"<LOGIN authenticationkey='" . $TuffTuffTime_options['tufftufftime_api_key'] . "' />" .
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
      curl_setopt_array($session, self::$curl_options);
      curl_setopt($session, CURLOPT_POSTFIELDS, "$xml");
      $response = curl_exec($session);
      curl_close($session);

      // Check if we got a response
      if(!$response) :
        throw new \Exception("Could not get arriving");
  		endif;

      $arriving = json_decode( $response, true );

		  set_transient( $transient_id, $arriving, 5 * MINUTE_IN_SECONDS );

    endif;

    return $arriving;

  }

  /**
   * Get departing trains/busses to the station
   *
   * @since     1.0.0
   * @return    array
   */
  public function load_departing( $TuffTuffTime_options, $station_ID ) {

    $transient_id = '_TuffTuffTime_departing_' . $station_ID;

    if ( false === ( $departing = get_transient( $transient_id ) ) ) :

      $xml = "<REQUEST>" .
              "<LOGIN authenticationkey='" . $TuffTuffTime_options['tufftufftime_api_key'] . "' />" .
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
      curl_setopt_array( $session, self::$curl_options );
      curl_setopt( $session, CURLOPT_POSTFIELDS, "$xml" );
      $response = curl_exec( $session );
      curl_close( $session );

      // Check if we got a response
      if(!$response) :
        throw new \Exception("Could not get departing");
  		endif;

      $departing = json_decode( $response, true );

		  set_transient( $transient_id, $departing, 5 * MINUTE_IN_SECONDS );

		endif;

    return $departing;

  }

	/**
   * Retrives the stations from the api
   *
	 * @since     1.0.0
   * @return    json-array
  */
  public function load_stations( $TuffTuffTime_options ) {

    $transient_id = '_TuffTuffTime_stations';

    if ( false === ( $stations = get_transient( $transient_id ) ) ) :

      $xml = "<REQUEST>" .
                "<LOGIN authenticationkey='" . $TuffTuffTime_options['tufftufftime_api_key'] . "' />" .
                "<QUERY objecttype='TrainStation'>" .
                 "<FILTER/>" .
                 "<INCLUDE>AdvertisedLocationName</INCLUDE>" .
                  "<INCLUDE>LocationSignature</INCLUDE>" .
                "</QUERY>" .
              "</REQUEST>";

      // Open up curl session and fire of the request
      $session = curl_init();
      curl_setopt_array( $session, self::$curl_options );
      curl_setopt( $session, CURLOPT_POSTFIELDS, "$xml" );
      $response = curl_exec( $session );
      curl_close( $session );

      // Check if we got a response
      if ( !$response ) :
        throw new \Exception("Could not get stations");
      endif;

      $stations = json_decode( $response, true );

		  set_transient( $transient_id, $stations, WEEK_IN_SECONDS );

		endif;

    return $stations;

  }

  /**
   * Get the stationID for a station.
   *
   * @since     1.0.0
   * @param     string $name - Name of the station
   * @return    string
   */
  public function get_station_ID( $TuffTuffTime_options, $stations, $name ) {

    $foundID = "";

    // Loop through the returned array to find the id
    foreach( $stations['RESPONSE']['RESULT']['0']['TrainStation'] as $station ) :

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
   * @since     1.0.0
   * @param     string $id - ID of the station
   * @return    string
   */
  public function get_station_name( $id ) {

    $foundName = '';

    // Loop through the returned array to find the name
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
