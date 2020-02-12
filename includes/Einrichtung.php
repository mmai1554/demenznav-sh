<?php

namespace mnc;


class Einrichtung {

	const CPT_EINRICHTUNG = 'einrichtung';
	const TAXONOMY_KLASSIFIKATION = 'klassifikation';
	const TAXONOMY_KREIS = 'kreis';

	public static function registerCPT() {

		$key_cpt            = self::CPT_EINRICHTUNG;
		$tax_klassifikation = self::TAXONOMY_KLASSIFIKATION;
		$tax_kreis          = self::TAXONOMY_KREIS;

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
			'taxonomies'          => array( $tax_klassifikation, $tax_kreis, 'post_tag' ),
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
		$labels = array(
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
		$args   = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		);
		register_taxonomy( $tax_klassifikation, array( $key_cpt ), $args );

		// Landkreise
		$labels = array(
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
		$args   = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		);
		register_taxonomy( $tax_kreis, array( $key_cpt ), $args );

		self::modifyAdminColumns();

	}

	public static function modifyAdminColumns() {
		// Add Projektnummer to Column:

		add_filter( 'manage_' . self::CPT_EINRICHTUNG . '_posts_columns', function ( $columns ) {
			return
				[
					'cb'                                        => '<input type="checkbox" />',
					'title'                                     => __( 'Titel' ),
					'plz'                                       => __( 'PLZ' ),
					'address'                                   => __( 'Adresse' ),
					'location'                                  => __( 'GeoLoc' ),
					'taxonomy-' . self::TAXONOMY_KLASSIFIKATION => __( 'Klassifikation' ),
					'taxonomy-' . self::TAXONOMY_KREIS          => __( 'Kreis' ),
				];
		} );

		add_filter( 'manage_' . self::CPT_EINRICHTUNG . '_posts_custom_column', function ( $column, $post_id ) {
			switch ( $column ) {
				case 'address':
					$adresse = Presenter::init()->adresse( $post_id );
					echo( $adresse );
					break;
				case 'plz':
					$plz = Presenter::init()->plz( $post_id );
					echo( $plz );
					break;
//				case 'synced':
//					/** @var \wpdb $wpdb */
//					global $wpdb;
//					$sql = "SELECT * FROM wp_latlong WHERE post_id='$post_id' LIMIT 1";
//					$row = $wpdb->get_row( $sql );
//					if ( null !== $row ) {
//						echo( $row->lat . '/' . $row->lng );
//					} else {
//						echo( '- not synced -' );
//					}
//					break;
				case 'location':
					$a = get_field( 'standort', $post_id, true );
					if ( isset( $a['lng'] ) ) {
						$s = $a['lat'] . ' / ' . $a['lng'];
						echo( $s );
					}
					global $wpdb;
					$sql = "SELECT * FROM wp_latlong WHERE post_id='$post_id' LIMIT 1";
					$row = $wpdb->get_row( $sql );
					if ( null !== $row ) {
						echo( '<div>synced data:' . $row->lat . '/' . $row->lng . '</div>' );
					} else {
						echo( '<span style="color:#ca4a1f;">[not synced with wp_latlng] please save or use admin > sync</span>' );
					}
					break;
					break;
			}
		}, 10, 3 );

		// Make it sortable:
		// 1st: Column Title clickable:
		add_filter( 'manage_edit-' . self::CPT_EINRICHTUNG . '_sortable_columns', function ( $columns ) {
			$columns['plz']      = 'plz';
			$columns['address']  = 'address';
			$columns['location'] = 'standort';

			return $columns;
		} );
		// 2nd: Hook into posts query (use it for searching too:)
		add_action( 'pre_get_posts', function ( \WP_Query $query ) {
			if ( ! is_admin() ) {
				return '';
			}
			if(!function_exists('\get_current_screen')) {
				require_once(ABSPATH . 'wp-admin/includes/screen.php');
			}
			$screen    = \get_current_screen();
			$post_type = $post_type = $query->get( 'post_type' );
			if ( ! is_admin() || ( isset( $screen->post_type ) && self::CPT_EINRICHTUNG != $screen->post_type ) || self::CPT_EINRICHTUNG != $post_type ) {
				return;
			}
			$orderby = $query->get( 'orderby' );
			if ( 'standort' == $orderby ) {
				$meta_query = array(
					array(
						'key'     => 'standort',
						'value'   => '',
						'compare' => '=',
					),
				);
				$query->set( 'meta_query', $meta_query );
				$query->set( 'meta_key', 'standort' );
				$query->set( 'orderby', 'meta_value' );
				$query->set( 'order', 'DESC' );
				// $query->set( 'order', 'ASC' );
			}
//			$s = $query->get( 's' );
//
//			return $s;

		} );

	}


}