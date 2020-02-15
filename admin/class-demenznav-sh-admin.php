<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://reboom.de
 * @since      1.0.0
 *
 * @package    Demenznav_Sh
 * @subpackage Demenznav_Sh/admin
 */

use Aws\Signature\SignatureV4;
use GuzzleHttp\Psr7\Request;
use mnc\Einrichtung;
use mnc\Glossar;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Demenznav_Sh
 * @subpackage Demenznav_Sh/admin
 * @author     ReBoom GmbH <m.mai@reboom.de>
 */
class Demenznav_Sh_Admin {

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
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

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
		 * defined in Demenznav_Sh_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Demenznav_Sh_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/demenznav-sh-admin.css', array(), $this->version, 'all' );

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
		 * defined in Demenznav_Sh_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Demenznav_Sh_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/demenznav-sh-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function register_custom_post_types() {
		Einrichtung::registerCPT();
		Glossar::registerCPT();
	}


	/**
	 * Workflow for Einrichtung Administration and syncing
	 */
	function admin_einrichtung() {
		// Dieser Hook ist steuert die Methode, die die eigene Admin Seite aufbaut:
		add_submenu_page( 'edit.php?post_type=einrichtung', 'GeoData Sync', 'Admin', 'administrator', 'einrichtung_admin_page',
			[
				$this,
				'einrichtung_admin_page'
			]
		);
		// Dieser Hook steuert, welche Methode nach submit (action) aufgerufen wird:
		add_action( 'admin_action_einrichtung_sync_latlang', [ $this, 'einrichtung_sync_latlang' ] );

		// Sync LatLong Table with posts:
		add_action( 'save_post', [ $this, 'sync_latlong_on_save' ] );
		add_action( 'delete_post', [ $this, 'sync_latlong_on_delete' ] );
	}

	public function einrichtung_sync_latlang() {
		// check if table exists:
		global $post;
		$args     = array(
			'post_type'      => 'einrichtung',
			'posts_per_page' => - 1,
		);
		$objQuery = new WP_Query( $args );
		while ( $objQuery->have_posts() ) {
			$objQuery->the_post();
			$this->save_latlng( $post );
		}
		echo( 'Done!' );
	}


	public function sync_latlong_on_save( $post_id ) {
		$post = get_post( $post_id );
		if ( ! is_admin() || $post->post_type != Einrichtung::CPT_EINRICHTUNG ) {
			return;
		}
		$this->save_latlng( $post );
	}

	// Delete Entry if
	public function sync_latlong_on_delete( $post_id ) {
		global $wpdb;
		if ( $wpdb->get_var( $wpdb->prepare( 'SELECT post_id FROM wp_latlong WHERE post_id = %d', $post_id ) ) ) {
			$wpdb->query( $wpdb->prepare( 'DELETE FROM wp_latlong WHERE post_id = %d', $post_id ) );
		}
	}

	/**
	 * Syncronisiert die Latitude und Longitude Daten des CPTS Einrichtung mit der externen Tabelle
	 * wp_latlng
	 *
	 * @param WP_Post $post
	 */
	protected function save_latlng( WP_Post $post ) {
		global $wpdb;
		$table = 'wp_latlong';
		// Check that we are editing the right post type
		if ( 'einrichtung' != $post->post_type ) {
			return;
		}
		$standort = get_field( 'standort', $post->ID );
		if ( ! is_array( $standort ) ) {
			return;
		}
		// Check if we have a lat/lng stored for this property already
		$check_link = $wpdb->get_row( "SELECT * FROM wp_latlong WHERE post_id = '" . $post->ID . "'" );
		// Update the row with possible new values:
		if ( $check_link != null ) {
			$wpdb->update(
				$table,
				[
					'lat' => $standort['lat'],
					'lng' => $standort['lng'],
				],
				[ 'post_id' => $post->ID ],
				[ '%f', '%f' ]
			);
		} else {
			// We do not already have a lat lng for this post. Insert row
			$wpdb->insert(
				$table,
				[
					'post_id' => $post->ID,
					'lat'     => $standort['lat'],
					'lng'     => $standort['lng'],
				],
				[
					'%d',
					'%f',
					'%f'
				]
			);
		}

	}


	function einrichtung_admin_page() {
		?>
        <div class="wrap">
            <h3 class="wp-heading-inline">Admin Page - Achtung!
            Die folgenden Aktionen mit Vorsicht einsetzen...
            </h3>
            <p>Lat / Lang mit externer Tabelle synchronisieren:</p>
            <form method="POST" action="<?php echo admin_url( 'admin.php' ); ?>">
                <input type="hidden" name="action" value="einrichtung_sync_latlang"/>
                <input type="submit" value="Lat Lang mit externer Tabelle synchronisieren"/>
            </form>
            <br>
        </div>
        <br>
		<?php
	}

	public function init_elasticsearch_aws_service() {
		add_filter( 'http_request_args', function ( array $args, string $url ): array {
		    $url = str_replace('http://', 'https://', $url);
			// $host = parse_url( $url, PHP_URL_HOST );

//			if ( EP_HOST !== $host ) {
//				return $args;
//			}
			$request = new Request( $args['method'], $url, $args['headers'], $args['body'] );
			$signer  = new SignatureV4( 'es', 'eu-central-1' ); // replace with your region.
			if ( defined( 'ELASTICSEARCH_AWS_KEY' ) ) {
				$credentials = new Aws\Credentials\Credentials( ELASTICSEARCH_AWS_KEY, ELASTICSEARCH_AWS_SECRET );
			} else {
				$credentials = new Aws\Credentials\InstanceProfileProvider();
			}

			$signed_request                   = $signer->signRequest( $request, $credentials );
			$args['headers']['Authorization'] = $signed_request->getHeader( 'Authorization' )[0];
			$args['headers']['X-Amz-Date']    = $signed_request->getHeader( 'X-Amz-Date' )[0];

			return $args;
		}, 10, 2 );


	}


}
