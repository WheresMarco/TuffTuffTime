<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://wheresmar.co
 * @since             1.0.0
 * @package           TuffTuffTime
 *
 * @wordpress-plugin
 * Plugin Name:       TuffTuffTime
 * Plugin URI:        https://github.com/WheresMarco/TuffTuffTime
 * Description:       A WordPress widget that shows the current and future stops at a train station in Sweden.
 * Version:           2.0.0
 * Author:            Marco Hyyryläinen
 * Author URI:        http://wheresmar.co
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       TuffTuffTime
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-TuffTuffTime.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_TuffTuffTime() {

	$plugin = new TuffTuffTime();
	$plugin->run();

}
run_TuffTuffTime();
