<?php

/**
 * Fired during plugin activation
 *
 * @link       https://reboom.de
 * @since      1.0.0
 *
 * @package    Demenznav_Sh
 * @subpackage Demenznav_Sh/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Demenznav_Sh
 * @subpackage Demenznav_Sh/includes
 * @author     ReBoom GmbH <m.mai@reboom.de>
 */
class Demenznav_Sh_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		self::create_tables();
	}

	public static function create_tables() {
		global $wpdb;
		$table_name      = $wpdb->prefix . "latlong";
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  post_id bigint(9) NOT NULL,
  lat float NOT NULL,
  lng float NOT NULL,
  PRIMARY KEY  (id),
  KEY post_id (post_id)
) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

}
