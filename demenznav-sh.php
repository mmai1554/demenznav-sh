<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://reboom.de
 * @since             1.0.0
 * @package           Demenznav_Sh
 *
 * @wordpress-plugin
 * Plugin Name:       Demenznavigator S-H
 * Plugin URI:        demenznav-sh
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            ReBoom GmbH
 * Author URI:        https://reboom.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       demenznav-sh
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DEMENZNAV_SH_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-demenznav-sh-activator.php
 */
function activate_demenznav_sh() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-demenznav-sh-activator.php';
	Demenznav_Sh_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-demenznav-sh-deactivator.php
 */
function deactivate_demenznav_sh() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-demenznav-sh-deactivator.php';
	Demenznav_Sh_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_demenznav_sh' );
register_deactivation_hook( __FILE__, 'deactivate_demenznav_sh' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-demenznav-sh.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_demenznav_sh() {

	$plugin = new Demenznav_Sh();
	$plugin->run();

}
run_demenznav_sh();
