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

	}


}