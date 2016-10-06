<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and settings for the plugin.
 *
 * @package    TuffTuffTime
 * @subpackage TuffTuffTime/admin
 * @author     Marco Hyyryläinen <marco@wheresmar.co>
 */
class TuffTuffTime_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 * @param    string    $plugin_name       The name of this plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

  /**
	 * Register the settings fields that are used.
	 *
	 * @since    2.0.0
	 */
	public function register_settings() {

		// Register and create settings section for the plugin
    register_setting('TuffTuffTime', 'tufftufftime_options');
    add_settings_section( 'tufftufftime_options', '', array( $this, 'create_settings_section' ), 'general' );

		// Add settings field for API Key
		add_settings_field(
		  'api_key',
		  __( 'Trafiklab API-key', 'TuffTuffTime' ),
			array( $this, 'create_textbox' ),
		  'general',
		  'tufftufftime_options',
			[
	    	'label_for' => 'tufftufftime_api_key',
	    ]
		);

	}

  /**
	 * Callback function for add_settings_section for TuffTuffTime
	 *
	 * @since    2.0.0
	 */
  public function create_settings_section( $arg ) {

  	echo '<h2 class="title">' . $this->plugin_name . '</h2>';
    echo '<p>' . __( 'You have to register an account at <a href="https://www.trafiklab.se/" target="_blank">Trafiklab</a> and create an application that uses <a href="https://www.trafiklab.se/api/trafikverket-oppet-api" target="_blank">Trafikverkets Open API</a> to use TuffTuffTime. You should then be able to create an API-key that can be used with this plugin.', 'TuffTuffTime' ) . '</p>';

  }

  /**
   * Generic callback function for add_settings_field to create textboxes
   *
   * @since    2.0.0
   */
	public function create_textbox( $args ) {

		$options = get_option('tufftufftime_options');
		?>
			<input
        type="text"
        id="<?php echo esc_attr($args['label_for']); ?>"
        name="tufftufftime_options[<?php echo esc_attr($args['label_for']); ?>]"
        value="<?php echo $options[$args['label_for']]; ?>"
        class="regular-text code" />
		<?php

	}

}
