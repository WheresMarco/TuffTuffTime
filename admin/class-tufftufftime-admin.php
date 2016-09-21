<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wheresmar.co
 * @since      1.0.0
 *
 * @package    Tufftufftime
 * @subpackage Tufftufftime/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tufftufftime
 * @subpackage Tufftufftime/admin
 * @author     Marco Hyyryläinen <marco@wheresmar.co>
 */
class Tufftufftime_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tufftufftime-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tufftufftime-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function register_settings() {

		// Register and create settings section for the plugin
    register_setting('tufftufftime', 'tufftufftime_options');
    add_settings_section( 'tufftufftime_options', '', function() {}, 'tufftufftime' );

		// Add settings field for API Key
		add_settings_field(
		  'api_key',
		  'API Key',
			array( $this, 'create_textbox' ),
		  'tufftufftime',
		  'tufftufftime_options',
			[
	    	'label_for' => 'tufftufftime_api_key',
	    ]
		);

	}

	public function create_textbox($args) {

		$options = get_option('tufftufftime_options');
		?>
			<input type="text" id="<?= esc_attr($args['label_for']); ?>" name="tufftufftime_options[<?= esc_attr($args['label_for']); ?>]" value="<?php echo $options[$args['label_for']]; ?>"></input>
		<?php

	}

	/**
	 * Create the options page.
	 *
	 * @since    1.0.0
	 */
	public function create_options_page() {

		add_submenu_page(
			'options-general.php',
			'TuffTuffTime',
			'TuffTuffTime',
			'manage_options',
			'tufftufftime',
			function() {

				if ( !current_user_can('manage_options') ) :
		      return;
		    endif;

				include( plugin_dir_path( __FILE__ ) . 'partials/tufftufftime-admin-options.php' );

			}
		);

	}

}
