<?php


namespace mnc;

class Filtersuche extends Umkreissuche {


//	const QUERY_VAR_PLZ = 'mnc-plz';
//	const QUERY_VAR_KLASSIFIKATION = 'mnc-einrichtung';
//
//	const RADIANS = 0.0174532925199; // = PI / 180;
//
//	protected $arrErrors = [];
//
//	/**
//	 * @var null | \WP_Term
//	 */
//	protected $objKlassifikation = null;
//	protected $zipcode = '';
//	// radius search in KM:
//	protected $radius_min = 0;
//	protected $radius_max = 100; // So default is between 0 and 100 KM
//
//	protected $action_fired = null;
//
//	/**
//	 * @var null | GeoData
//	 */
//	protected $objGeoData = null;


//	/**
//	 * add query vars to WP Query Vars
//	 * called in register_query_vars in Demenznav_Sh_Public
//	 *
//	 * @param $vars
//	 *
//	 * @return array
//	 */
//	public static function appendQueryVars( $vars ) {
//		$vars[] = self::QUERY_VAR_KLASSIFIKATION;
//		$vars[] = self::QUERY_VAR_PLZ;
//
//		return $vars;
//	}


	public function validateInput() {


		// check Klassifikation:

		$klass_id = sanitize_text_field( get_query_var( self::QUERY_VAR_KLASSIFIKATION ) );
		if ( $klass_id ) {
			$klass_id                = (int) str_replace( 'K', '', $klass_id );
			$this->objKlassifikation = get_term( $klass_id, 'klassifikation' );
		}


		$plz = sanitize_text_field( get_query_var( self::QUERY_VAR_PLZ ) );

		if ( $plz ) {
			$re = '/^[0-9]{1,5}$/m';
			if ( ! preg_match( $re, $plz ) ) {
				$this->arrErrors[ self::QUERY_VAR_PLZ ] = 'Bitte eine korrekte Postleitzahlsuche eingeben, erlaubt sind vollständige oder Anfangsbereiche von Postleitzahlen. ';

				return false;
			}
			$this->zipcode    = $plz;
			$this->objGeoData = new GeoData( $plz );
			$this->objGeoData->init();
			if ( ! $this->objGeoData->isPLZInBundesland( 'Schleswig-Holstein' ) ) {
				$this->arrErrors[ self::QUERY_VAR_PLZ ] = 'Zurzeit sind nur Suchen in Schleswig-Holstein möglich.
				Die Postleitzahl ' . $plz . ' ist nicht hinterlegt. Bitte wählen Sie eine Postleitzahl aus Schleswig-Holstein.';

				return false;
			}
		}

		return true;
	}

	public function getWPQuery() {
		global $wp_query;
		$posts_per_page = get_option( 'posts_per_page', 10 );
		$paged          = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		$offset         = ( $paged - 1 ) * $posts_per_page;
		$args           = array(
			'post_type'      => Einrichtung::CPT_EINRICHTUNG,
			'posts_per_page' => $posts_per_page,
			'paged'          => $paged,
			'offset'         => $offset,
		);
		if ( $this->doesKlassifikationExist() ) {
			$args['tax_query'] = [
				[
					'taxonomy' => Einrichtung::TAXONOMY_KLASSIFIKATION,
					'terms'    => $this->objKlassifikation->term_id,
				]
			];
		}
		add_filter( 'posts_distinct', [ $this, 'query_distinct' ] );
		add_filter( 'posts_join', [ $this, 'add_join_geocode' ] );
		add_filter( 'posts_fields', [ $this, 'add_fields_geocode' ], 10, 2 );
		add_filter( 'posts_where', [ $this, 'filter_valid_distance' ], 10, 2 );
		add_filter( 'posts_orderby', [ $this, 'orderby_distance' ], 10, 2 );
		add_filter( 'posts_groupby', [ $this, 'groupby_no' ] );
		$wp_query = new \WP_Query( $args );
		remove_filter( 'posts_distinct', [ $this, 'query_distinct' ] );
		remove_filter( 'posts_join', [ $this, 'add_join_geocode' ] );
		remove_filter( 'posts_fields', [ $this, 'add_fields_geocode' ] );
		remove_filter( 'posts_where', [ $this, 'filter_valid_distance' ] );
		remove_filter( 'posts_orderby', [ $this, 'orderby_distance' ] );
		remove_filter( 'posts_groupby', [ $this, 'groupby_no' ] );
		// return $wp_query;
	}


}