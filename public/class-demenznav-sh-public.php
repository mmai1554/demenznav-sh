<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://reboom.de
 * @since      1.0.0
 *
 * @package    Demenznav_Sh
 * @subpackage Demenznav_Sh/public
 */

use mnc\Umkreissuche;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Demenznav_Sh
 * @subpackage Demenznav_Sh/public
 * @author     ReBoom GmbH <m.mai@reboom.de>
 */
class Demenznav_Sh_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * hook method for inlcude own params in WP Query vars
	 *
	 * @param $vars
	 *
	 * @return array
	 */
	public function register_query_vars( $vars ) {
		$vars = Umkreissuche::appendQueryVars( $vars );

		return $vars;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/demenznav-sh-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key='.MI_GOOGLE_MAPS_API_KEY );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/demenznav-sh-public.js', [ 'jquery' ], $this->version, true );
		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/gmaps.js', [ 'jquery' ], $this->version, true );

		wp_localize_script( $this->plugin_name, 'umkreissuche', [ 'ajaxurl' => admin_url( 'admin-ajax.php' ) ] );

	}

	public function register_global_variables() {
		global $mnc_error;
		$mnc_error = new WP_Error();
	}

	public function ajax_umkreissuche() {
		echo get_bloginfo( 'title' );
		die();
	}


	function register_presenter() {
		add_action( 'format_website', array( $this, 'format_website' ) );
	}

	/**
	 * returns a formatted website link
	 * use only on website fields
	 * true: echos the formatted value
	 * false: returns the formatted value as string
	 *
	 * @param $fieldname
	 *
	 * @return string
	 */
	function format_website( $fieldname ) {
		$url = get_field( $fieldname, false, false );
		if ( ! $url ) {
			echo( '' );
		}

		echo sprintf( '<a href="%s" target="_blank" title="Website %s in neuem Fenster öffnen...">%s</a>',
			$url, $url, $url
		);
	}


	function register_shortcodes() {
		$this->register_shortcode_mi_karte();
		$this->register_shortcode_searchform();
	}

	protected function einrichtung_exists( $id ) {
		return false;
	}

	function clear_error_messages() {

	}

	protected function addError( $field, $message ) {
		if ( ! isset( $mnc_error ) ) {
			$mnc_error = new WP_Error;
		}
		$mnc_error->add( $field, $message );
	}

	protected function hasErrors() {
		global $mnc_error;

		return 1 > count( $mnc_error->get_error_messages() );
	}

	protected function register_shortcode_input_klassifikation() {

		add_shortcode( 'input_klassifikationen', function () {
			$taxonomies = get_terms( [
				'taxonomy'   => 'klassifikation',
				'hide_empty' => false,
				'parent'     => 0
			] );
			$list       = [];
			$template   = '<div class="form-check"><input class="form-check-input" type="checkbox" value="klassif[][%s]" id="K_%s"><label class="form-check-label" for="K_%s">%s</label></div>';
			foreach ( $taxonomies as $tax ) {
				$list[] = sprintf( $template,
					$tax->term_id,
					$tax->term_id,
					$tax->term_id,
					$tax->name
				);
			}

			return implode( "\n", $list );
		} );

	}


	protected function register_shortcode_searchform() {
		add_shortcode( 'mi_suchmaske', function () {
			$file = get_stylesheet_directory() . '/templates/form_umkreissuche.php';
			if ( ! file_exists( $file ) ) {
				return '';
			}
			ob_start();
			require $file;
			$var = ob_get_contents();
			ob_end_clean();

			return $var;
		} );
	}

	protected function register_shortcode_mi_karte() {
		add_shortcode( 'mi_karte', function () {
			$file = get_stylesheet_directory() . '/includes/karte.php';
			if ( ! file_exists( $file ) ) {
				return '';
			}
			ob_start();
			require $file;
			$var = ob_get_contents();
			ob_end_clean();

			return $var;
		} );
	}


}
