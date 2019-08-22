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
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/EinrichtungPresenter.php';

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/demenznav-sh-public.js', array( 'jquery' ), $this->version, false );

	}

	function register_presenter() {
		add_action( 'format_website', array( $this, 'format_website' ) );
		add_action( 'format_contact', array( $this, 'format_contact' ) );
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

	function format_contact() {
		$get_li  = function ( $label, $content, $icon ) {
			$a   = [];
			$a[] = '<li>';
			$a[] = '<div class="icon-wrap">';
			$a[] = '<span class="fl-icon">';
			$a[] = '<i class="ua-icon ' . $icon . '"></i>';
			$a[] = '</span>';
			$a[] = '<div class="fl-icon-text">';
			$a[] = $label  . $content;
			$a[] = '</div>';
			$a[] = '</div>';
			$a[] = '</li>';

			return implode( "\n", $a );
		};
		$contact = [];
		if ( $url = get_field( 'website', false, false ) ) {
			$content            = sprintf( '<a href="%s" target="_blank" title="Website %s in neuem Fenster öffnen...">%s</a>', $url, $url, $url );
			$contact['website'] = [ 'Web: ', $content, 'ua-icon-globe2' ];
		}
		if ( $email = get_field( 'email', false, false ) ) {
			$content          = sprintf( '<a href="%s" target="_blank" title="Mailprogramm öffnen und E-Mail an %s senden...">%s</a>', $email, $email, $email );
			$contact['email'] = [ 'E-Mail: ', $content, 'ua-icon-icon-6-mail-envelope-closed2' ];
		}
		if ( get_field( 'strasse' ) ) {
			$content            = get_field( 'strasse' ) . '<br>' . get_field( 'plz' ) . ' ' . get_field( 'ort' );
			$contact['adresse'] = [ '', $content, 'ua-icon-location-pin' ];
		}
		if ( get_field( 'telefon' ) ) {
			$content            = get_field( 'telefon' );
			$contact['telefon'] = [ 'Telefon: ', $content, 'ua-icon-phone3' ];
		}
		if ( count( $contact ) == 0 ) {
			return '';
		}
		$html = '<ul>';
		foreach ( $contact as $key => $line ) {
			$html .= $get_li( $line[0], $line[1], $line[2] );
		}
		$html .= '</ul>';
		echo($html);
	}


}
