<?php


namespace mnc;

class Umkreissuche {


	const QUERY_VAR_PLZ = 'mnc-plz';
	const QUERY_VAR_KLASSIFIKATION = 'mnc-einrichtung';

	const RADIANS = 0.0174532925199; // = PI / 180;

	protected $arrErrors = [];

	/**
	 * @var null | \WP_Term
	 */
	protected $objKlassifikation = null;
	protected $zipcode = '';
	// radius search in KM:
	protected $radius_min = 0;
	protected $radius_max = 100; // So default is between 0 and 100 KM

	protected $action_fired = null;

	/**
	 * @var null | GeoData
	 */
	protected $objGeoData = null;

	public function __construct() {
	}

	/**
	 * add query vars to WP Query Vars
	 * called in register_query_vars in Demenznav_Sh_Public
	 *
	 * @param $vars
	 *
	 * @return array
	 */
	public static function appendQueryVars( $vars ) {
		$vars[] = self::QUERY_VAR_KLASSIFIKATION;
		$vars[] = self::QUERY_VAR_PLZ;

		return $vars;
	}


	public function setRadius( $min, $max ) {
		$this->radius_min = $min;
		$this->radius_max = $max;
	}

	public function hasErrors() {
		return count( $this->arrErrors ) > 0;
	}

	public function getErrors() {
		return $this->arrErrors;
	}

	protected function doesKlassifikationExist() {
		return $this->objKlassifikation !== null;
	}

	public function showSearchform() {
		return true;
		// return $this->hasErrors() || ! $this->isActionFired();
	}

	public function isActionFired() {
		if ( $this->action_fired === null ) {
			$a                  = get_query_var( self::QUERY_VAR_PLZ, false );
			$a                  = $a || get_query_var( self::QUERY_VAR_KLASSIFIKATION, false );
			$this->action_fired = $a;
		}

		return $this->action_fired;
	}


	/**
	 * @return \WP_Term|null
	 */
	public function getKlassifikation() {
		return $this->objKlassifikation;
	}

	public function getZipcode() {
		return $this->zipcode;
	}

	public function getRadius() {
		return $this->radius_max;
	}

	public function validateInput() {


		// check Klassifikation:

		$klass_id = sanitize_text_field( get_query_var( self::QUERY_VAR_KLASSIFIKATION ) );
		if ( ! $klass_id ) {
			$this->arrErrors[ self::QUERY_VAR_KLASSIFIKATION ] = 'Bitte Hilfsangebot auswählen';

			return false;
		}
		$klass_id                = (int) str_replace( 'K', '', $klass_id );
		$this->objKlassifikation = get_term( $klass_id, 'klassifikation' );

		if ( null === $this->objKlassifikation ) {
			$this->arrErrors[ self::QUERY_VAR_KLASSIFIKATION ] = 'Bitte Hilfsangebot aus der Liste der vorhandenen Hilfsangebote auswählen';

			return false;
		}

		// check  PLZ:

		$plz = sanitize_text_field( get_query_var( self::QUERY_VAR_PLZ ) );

		if ( ! $plz ) {
			$this->arrErrors[ self::QUERY_VAR_PLZ ] = 'Bitte Postleitzahl wählen';

			return false;
		}

		$re = '/^[0-9]{1,5}$/m';
		if ( ! preg_match( $re, $plz ) ) {
			$this->arrErrors[ self::QUERY_VAR_PLZ ] = 'Bitte eine korrekte Postleitzahlsuche eingeben, erlaubt sind vollständige oder Anfangsbereiche von Postleitzahlen. ';

			return false;
		}

		// zipcode seems ok but is it a SH zipcode?
		$this->zipcode = $plz;
		// at this  point we are querying the geodata to be able to chek against a valid zipcode (SH zipcode)

		$this->objGeoData = new GeoData( $plz );
		$this->objGeoData->init();

		// Schleswig-Holstein
		if ( ! $this->objGeoData->isPLZInBundesland( 'Schleswig-Holstein' ) ) {
			$this->arrErrors[ self::QUERY_VAR_PLZ ] = 'Zurzeit sind nur Suchen in Schleswig-Holstein möglich.
			Die Postleitzahl ' . $plz . ' ist nicht hinterlegt. Bitte wählen Sie eine Postleitzahl aus Schleswig-Holstein.';

			return false;
		}

		// everything seems ok:
		return true;
	}

	public function getWPQuery() {
		global $wp_query;
		$posts_per_page = get_option( 'posts_per_page', 10 );
		$paged          = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		$offset         = ( $paged - 1 ) * $posts_per_page;
		$args           = array(
			'post_type'      => 'einrichtung',
			'posts_per_page' => $posts_per_page,
			'paged'          => $paged,
			'offset'         => $offset,
			'tax_query'      => [
				[
					'taxonomy' => 'klassifikation',
					'terms'    => $this->objKlassifikation->term_id,
				]
			]
		);
		add_filter( 'posts_distinct', [ $this, 'query_distinct' ] );
		add_filter( 'posts_join', [ $this, 'add_join_geocode' ] );
		add_filter( 'posts_fields', [ $this, 'add_fields_geocode' ], 10, 2 );
		add_filter( 'posts_orderby', [ $this, 'orderby_distance' ], 10, 2 );
		add_filter( 'posts_groupby', [ $this, 'groupby_no' ] );
		$wp_query = new \WP_Query( $args );
		remove_filter( 'posts_distinct', [ $this, 'query_distinct' ] );
		remove_filter( 'posts_join', [ $this, 'add_join_geocode' ] );
		remove_filter( 'posts_fields', [ $this, 'add_fields_geocode' ] );
		remove_filter( 'posts_orderby', [ $this, 'orderby_distance' ] );
		remove_filter( 'posts_groupby', [ $this, 'groupby_no' ] );
		// return $wp_query;
	}

	public function addLatLngToQuery( \WP_Query $wp_query ) {
		add_filter( 'posts_join', [ $this, 'add_join_geocode' ] );
		add_filter( 'posts_fields', [ $this, 'add_fields_latlng' ], 10, 2 );
	}

	public function removeLatLngToQuery( \WP_Query $wp_query ) {
		remove_filter( 'posts_join', [ $this, 'add_join_geocode' ] );
		remove_filter( 'posts_fields', [ $this, 'add_fields_latlng' ] );
	}

	public function query_distinct() {
		return 'DISTINCT';
	}


	/**
	 * calculate the filter for the radius search
	 * only retrieve posts within the radius.
	 *
	 * Add a WP Filter Query (use in retrieving the posts with add_filter( 'posts_where' , ... );
	 *
	 * @param $where
	 *
	 * @return string
	 */
	public function filter_radius_query( $where ) {
		global $wpdb;
		// Specify the co-ordinates that will form
		// the centre of our search
		$lat    = $this->objGeoData->getLat();
		$lng    = $this->objGeoData->getLong();
		$radius = $this->radius_max;

		$table = 'wp_latlong';
		// Append our radius calculation to the WHERE
		$lcalc = trim( "( 6371 * acos( cos( radians(" . $lat . ") )
		                        * cos( radians( lat ) )
		                        * cos( radians( lng )
		                               - radians(" . $lng . ") )
		                        + sin( radians(" . $lat . ") )
		                          * sin( radians( lat ) ) ) )" );
		$where .= " AND $wpdb->posts.ID IN (SELECT post_id FROM $table WHERE $lcalc <= " . $radius . ")";

		return $where;
	}

	/**
	 * join the geocode table if a search is in progress
	 *
	 * @param $join
	 *
	 * @return string
	 */
	public function add_join_geocode( $join ) {
		global $wp_query, $wpdb;
		// if (!empty($wp_query->query_vars[\mnc\Umkreissuche::QUERY_VAR_PLZ])) {
		$join .= "LEFT JOIN wp_latlong ON ($wpdb->posts.ID = wp_latlong.post_id)";

		//}
		return $join;
	}

	/**
	 * We calculate the distance for each entry from to the given lat/lng in GeoData Object and store it in alias distance
	 *
	 * @param $fields
	 * @param $wp_query
	 *
	 * @return string
	 */
	public function add_fields_geocode( $fields, $wp_query ) {
		global $wpdb;

		$lat = $this->objGeoData->getLat();
		$lng = $this->objGeoData->getLong();
		// $sf = 3.14159 / 180 ;

		// $pythagoras = "(POW((wp_latlong.lng-$lng),2) + POW((wp_latlong.lat-$lat),2))";

		$great_circle_distance = "ACOS(SIN(RADIANS(wp_latlong.lat))*SIN(RADIANS($lat)) + COS(RADIANS(wp_latlong.lat))*COS(RADIANS($lat))*COS(RADIANS((wp_latlong.lng-$lng))))";

		$fields = "{$wpdb->posts}.*, wp_latlong.lat as latitude, wp_latlong.lng as longitude, $great_circle_distance as distance";

		return $fields;
	}

	/**
	 * add only lat and lng fields to select
	 * (in case we dont have a distance)
	 *
	 * @param $fields
	 * @param $wp_query
	 *
	 * @return string
	 */
	public function add_fields_latlng( $fields, $wp_query ) {
		global $wpdb;

		$fields = "{$wpdb->posts}.*, wp_latlong.lat as latitude, wp_latlong.lng as longitude";

		return $fields;
	}

	function orderby_distance( $orderby, $wp_query ) {
		$comma = "";
		if ( $orderby ) {
			$comma = ", ";
		}
		$orderby = "distance ASC" . $comma . $orderby;

		return $orderby;
	}

	/**
	 * when we orderby distance we cant group by post_id
	 *
	 * @param $groupby
	 *
	 * @return string
	 */
	function groupby_no( $groupby ) {
		global $wpdb;

		return '';

//		if( !is_search() ) {
//			return $groupby;
//		}
//
//		// we need to group on post ID
//
//		$mygroupby = "{$wpdb->posts}.ID";
//
//		if( preg_match( "/$mygroupby/", $groupby )) {
//			// grouping we need is already there
//			return $groupby;
//		}
//
//		if( !strlen(trim($groupby))) {
//			// groupby was empty, use ours
//			return $mygroupby;
//		}
//
//		// wasn't empty, append ours
//		return $groupby . ", " . $mygroupby;
	}

	/**
	 * calculates the distance between to points
	 *
	 * @param float $lat Latitude of Source
	 * @param float $lng Longitude of Source
	 * @param float $dest_lat Latitude of Dest
	 * @param float $dest_lng Longitude of Dest
	 *
	 * @return float
	 * @todo not the right place?
	 *
	 * @internal  the distance is calculated in the MySQL Query too.
	 */
	public static function getDistance( $lat, $lng, $dest_lat, $dest_lng ) {
		// $rad = M_PI / 180;
		$rad = self::RADIANS;
		//Calculate distance from latitude and longitude
		$theta = $lng - $dest_lng;
		$dist  = sin( $lat * $rad ) * sin( $dest_lat * $rad ) + cos( $lat * $rad )
		                                                        * cos( $dest_lat * $rad ) * cos( $theta * $rad );

		return acos( $dist ) / $rad * 60 * 1.853;
	}

	/**
	 * @return GeoData|null
	 */
	public function getGeoData() {
		return $this->objGeoData;
	}

	/**
	 * checks if distance calculation is set
	 * @return bool
	 */
	public function hasDistance() {
		return $this->objGeoData !== null;
	}


	/**
	 * only needed when the post is loaded as single instance
	 * not needed in the Loop! We have already an alias there
	 *
	 * @param \WP_Post $post
	 *
	 * @return float
	 */
	public function getDistanceOfEinrichtung( \WP_Post $post ) {
		$lat_from = $this->objGeoData->getLat();
		$lng_from = $this->objGeoData->getLong();
		$lat_to   = $post->latitude;
		$lng_to   = $post->longitude;

		return self::getDistance( $lat_from, $lng_from, $lat_to, $lng_to );
	}

}