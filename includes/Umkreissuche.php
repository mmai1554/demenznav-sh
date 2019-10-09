<?php


namespace mnc;

class Umkreissuche {


	const QUERY_VAR_PLZ = 'mnc-plz';
	const QUERY_VAR_KLASSIFIKATION = 'mnc-einrichtung';
	const QUERY_VAR_RADIUS = 'mnc-rmax';

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
		$vars[] = self::QUERY_VAR_RADIUS;

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
		return $this->hasErrors() || ! $this->isActionFired();
	}

	public function isActionFired() {
		if ( $this->action_fired === null ) {
			$a                  = get_query_var( self::QUERY_VAR_PLZ, false );
			$a                  = $a || get_query_var( self::QUERY_VAR_KLASSIFIKATION, false );
			$a                  = $a || get_query_var( self::QUERY_VAR_RADIUS, false );
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

		// check Radius:

		$radius = (int) sanitize_text_field( get_query_var( self::QUERY_VAR_RADIUS ) );
		if ( $radius <= 0 || $radius >= 100 ) {
			$radius = 100;
		}
		$this->radius_max = $radius;

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
			$this->arrErrors[ self::QUERY_VAR_PLZ ] = 'Zurzeit sind nur Umkreissuchen in Schleswig-Holstein in unserem System. Bitte wählen Sie ein Postleitzahl aus Schleswig-Holstein.';

			return false;
		}

		// everything seems ok:
		return true;
	}

	public function getWPQuery() {
		$args = array(
			'post_type'      => 'einrichtung',
			'posts_per_page' => 10,
			'tax_query'      => [
				[
					'taxonomy' => 'klassifikation',
					'terms'    => $this->objKlassifikation->term_id,
				]
			]
		);

		return new \WP_Query( $args );
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
	 * calculates the distance between to points
	 *
	 * @param $latitudeFrom
	 * @param $longitudeFrom
	 * @param $latitudeTo
	 * @param $longitudeTo
	 *
	 * @return float
	 */
	public static function getDistance( $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo ) {
		$rad = M_PI / 180;
		//Calculate distance from latitude and longitude
		$theta = $longitudeFrom - $longitudeTo;
		$dist  = sin( $latitudeFrom * $rad )
		         * sin( $latitudeTo * $rad ) + cos( $latitudeFrom * $rad )
		                                       * cos( $latitudeTo * $rad ) * cos( $theta * $rad );

		return acos( $dist ) / $rad * 60 * 1.853;
	}


}