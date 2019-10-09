<?php


namespace mnc;

class Umkreissuche {


	const QUERY_VAR_PLZ = 'mnc-plz';
	const QUERY_VAR_KLASSIFIKATION = 'mnc-einrichtung';

	protected $arrErrors = [];

	protected $objKlassifikation = null;
	protected $zipcode = '';
	// radius search in KM:
	protected $min = 0;
	protected $max = 100; // So default is between 0 and 100 KM

	/**
	 * @var null | GeoData
	 */
	protected $objGeoData = null;

	public function __construct() {
	}


	public function setRadius($min, $max) {
		$this->min = $min;
		$this->max = $max;
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

		$this->objGeoData = new GeoData($plz);
		$this->objGeoData->init();

		// Schleswig-Holstein
		if(!$this->objGeoData->isPLZInBundesland('Schleswig-Holstein')) {
			$this->arrErrors[ self::QUERY_VAR_PLZ ] = 'Zurzeit sind nur Umkreissuchen in Schleswig-Holstein in unserem System. Bitte wählen Sie ein Postleitzahl aus Schleswig-Holstein.';
			return false;
		}

		// everything seems ok:
		return true;
	}

	/**
	 * calculate the filter for the radius search
	 * only retrieve posts within the radius.
	 *
	 * Add a WP Filter Query (use in retrieving the posts with add_filter( 'posts_where' , ... );
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
		$radius = $this->max;

		$table  = 'wp_latlong';
		// Append our radius calculation to the WHERE
		$lcalc = trim("( 6371 * acos( cos( radians(" . $lat . ") )
		                        * cos( radians( lat ) )
		                        * cos( radians( lng )
		                               - radians(" . $lng . ") )
		                        + sin( radians(" . $lat . ") )
		                          * sin( radians( lat ) ) ) )");
		$where .= " AND $wpdb->posts.ID IN (SELECT post_id FROM $table WHERE $lcalc <= " . $radius . ")";
		return $where;
	}


}