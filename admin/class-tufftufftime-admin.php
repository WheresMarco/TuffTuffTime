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
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
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
	 * @since    1.0.0
	 */
	public function register_settings() {

		// Register and create settings section for the plugin
    register_setting('TuffTuffTime', 'TuffTuffTime_options');
    add_settings_section( 'TuffTuffTime_options', '', array( $this, 'create_settings_section' ), 'general' );

		// Add settings field for API Key
		add_settings_field(
		  'api_key',
		  'Trafiklab API-nyckel',
			array( $this, 'create_textbox' ),
		  'general',
		  'TuffTuffTime_options',
			[
	    	'label_for' => 'TuffTuffTime_api_key',
	    ]
		);

	}

  /**
	 * Callback function for add_settings_section for TuffTuffTime
	 *
	 * @since    1.0.0
	 */
  public function create_settings_section( $arg ) {

  	echo '<h2 class="title">' . $this->plugin_name . '</h2>';
    echo '<p>Du måste registrera ett konto på <a href="https://www.trafiklab.se/" target="_blank">Trafiklab</a> och skapa en applikation som använder <a href="https://www.trafiklab.se/api/trafikverket-oppet-api" target="_blank">Trafikverkets öppna API</a> för att kunna använda TuffTuffTime. Du bör då kunna generera en API-nyckel som kan användas med tillägget.</p>';

  }

  /**
   * Generic callback function for add_settings_field to create textboxes
   *
   * @since    1.0.0
   */
	public function create_textbox( $args ) {

		$options = get_option('TuffTuffTime_options');
		?>
			<input
        type="text"
        id="<?php echo esc_attr($args['label_for']); ?>"
        name="TuffTuffTime_options[<?php echo esc_attr($args['label_for']); ?>]"
        value="<?php echo $options[$args['label_for']]; ?>"
        class="regular-text code" />
		<?php

	}

}
