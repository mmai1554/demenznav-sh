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

		$key_cpt               = 'einrichtung';
		$key_category1_for_cpt = 'klassifikation';
		$key_category2_for_cpt = 'kreis';

		$text_domain = $key_cpt;
		$labels      = array(
			'name'                  => _x( 'Einrichtungen', 'Post Type General Name', $text_domain ),
			'singular_name'         => _x( 'Einrichtung', 'Post Type Singular Name', $text_domain ),
			'menu_name'             => __( 'Einrichtungen', $text_domain ),
			'name_admin_bar'        => __( 'Einrichtungen', $text_domain ),
			'archives'              => __( 'Archiv Einrichtungen', $text_domain ),
			'attributes'            => __( 'Weitere Attribute', $text_domain ),
			'parent_item_colon'     => __( 'Parent Item:', $text_domain ),
			'all_items'             => __( 'Alle Einrichtungen', $text_domain ),
			'add_new_item'          => __( 'Neu', $text_domain ),
			'add_new'               => __( 'Neu', $text_domain ),
			'new_item'              => __( 'Neu', $text_domain ),
			'edit_item'             => __( 'Edit', $text_domain ),
			'update_item'           => __( 'Update', $text_domain ),
			'view_item'             => __( 'Detail', $text_domain ),
			'view_items'            => __( 'Details', $text_domain ),
			'search_items'          => __( 'Suche', $text_domain ),
			'not_found'             => __( 'Not found', $text_domain ),
			'not_found_in_trash'    => __( 'Not found in Trash', $text_domain ),
			'featured_image'        => __( 'Featured Image', $text_domain ),
			'set_featured_image'    => __( 'Set featured image', $text_domain ),
			'remove_featured_image' => __( 'Remove featured image', $text_domain ),
			'use_featured_image'    => __( 'Use as featured image', $text_domain ),
			'insert_into_item'      => __( 'Insort into Aktivregion Projekte', $text_domain ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', $text_domain ),
			'items_list'            => __( 'Items list', $text_domain ),
			'items_list_navigation' => __( 'Items list navigation', $text_domain ),
			'filter_items_list'     => __( 'Filter items list', $text_domain ),
		);
		$rewrite     = array(
			'slug'       => 'einrichtungen',
			'with_front' => true,
			'pages'      => true,
			'feeds'      => true,
		);
		$args        = array(
			'label'               => __( $text_domain, $text_domain ),
			'description'         => __( 'Einrichtungen', $text_domain ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', ),
			'taxonomies'          => array( $key_category1_for_cpt, $key_category2_for_cpt, 'post_tag' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 3,
			'menu_icon'           => 'dashicons-admin-home',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'capability_type'     => 'page',
		);
		register_post_type( $key_cpt, $args );

		// Projektkategorie
		$text_domain = $key_category1_for_cpt;
		$labels      = array(
			'name'                       => _x( 'Klassifikationen', 'Taxonomy General Name', $text_domain ),
			'singular_name'              => _x( 'Klassifikation', 'Taxonomy Singular Name', $text_domain ),
			'menu_name'                  => __( 'Klassifikation', $text_domain ),
			'all_items'                  => __( 'All Items', $text_domain ),
			'parent_item'                => __( 'Parent Item', $text_domain ),
			'parent_item_colon'          => __( 'Parent Item:', $text_domain ),
			'new_item_name'              => __( 'New Item Name', $text_domain ),
			'add_new_item'               => __( 'Add New Item', $text_domain ),
			'edit_item'                  => __( 'Edit Item', $text_domain ),
			'update_item'                => __( 'Update Item', $text_domain ),
			'view_item'                  => __( 'View Item', $text_domain ),
			'separate_items_with_commas' => __( 'Separate items with commas', $text_domain ),
			'add_or_remove_items'        => __( 'Add or remove items', $text_domain ),
			'choose_from_most_used'      => __( 'Choose from the most used', $text_domain ),
			'popular_items'              => __( 'Popular Items', $text_domain ),
			'search_items'               => __( 'Search Items', $text_domain ),
			'not_found'                  => __( 'Not Found', $text_domain ),
			'no_terms'                   => __( 'No items', $text_domain ),
			'items_list'                 => __( 'Items list', $text_domain ),
			'items_list_navigation'      => __( 'Items list navigation', $text_domain ),
		);
		$args        = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		);
		register_taxonomy( $key_category1_for_cpt, array( $key_cpt ), $args );

		// Landkreise
		$text_domain = $key_category2_for_cpt;
		$labels      = array(
			'name'                       => _x( 'Kreise', 'Taxonomy General Name', $text_domain ),
			'singular_name'              => _x( 'Kreis', 'Taxonomy Singular Name', $text_domain ),
			'menu_name'                  => __( 'Kreis', 'text_domain' ),
			'all_items'                  => __( 'All Items', $text_domain ),
			'parent_item'                => __( 'Parent Item', $text_domain ),
			'parent_item_colon'          => __( 'Parent Item:', $text_domain ),
			'new_item_name'              => __( 'New Item Name', $text_domain ),
			'add_new_item'               => __( 'Add New Item', $text_domain ),
			'edit_item'                  => __( 'Edit Item', $text_domain ),
			'update_item'                => __( 'Update Item', $text_domain ),
			'view_item'                  => __( 'View Item', $text_domain ),
			'separate_items_with_commas' => __( 'Separate items with commas', $text_domain ),
			'add_or_remove_items'        => __( 'Add or remove items', $text_domain ),
			'choose_from_most_used'      => __( 'Choose from the most used', $text_domain ),
			'popular_items'              => __( 'Popular Items', $text_domain ),
			'search_items'               => __( 'Search Items', $text_domain ),
			'not_found'                  => __( 'Not Found', $text_domain ),
			'no_terms'                   => __( 'No items', $text_domain ),
			'items_list'                 => __( 'Items list', $text_domain ),
			'items_list_navigation'      => __( 'Items list navigation', $text_domain ),
		);
		$args        = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		);
		register_taxonomy( $key_category2_for_cpt, array( $key_cpt ), $args );


	}

	function register_einrichtung_admin() {
		// Dieser Hook ist steuert die Methode, die die eigene Admin Seite aufbaut:
		add_submenu_page( 'edit.php?post_type=einrichtung', 'admin', 'admin', 'administrator', 'einrichtung_admin_page', array( $this, 'einrichtung_admin_page' ) );
		// Dieser Hook steuert, welche Methode nach submit (action) aufgerufen wird:
		add_action( 'admin_action_einrichtung_sync_latlang', array( $this, 'einrichtung_sync_latlang' ) );
//		add_action( 'admin_action_einrichtung_map_brochures', array( $this, 'einrichtung_map_brochures' ) );
	}

	function einrichtung_sync_latlang() {
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
		add_action( 'admin_notices', function () {
			?>
            <div class="notice notice-success is-dismissible">
                <p><?php _e( 'Done!', 'demenzwegweisersh' ); ?></p>
            </div>
			<?php
		} );
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
		$check_link = $wpdb->get_row( "SELECT * FROM $table WHERE post_id = '" . $post->ID . "'" );
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
            <h3 class="wp-heading-inline">Admin Page for Einrichtungem (Nur für administrative Tätigkeiten!)</h3>
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


}
