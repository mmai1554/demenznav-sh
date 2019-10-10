<?php


namespace mnc;


/**
 * managing GeoData information of given Zipcode
 *
 * Class GeoData
 * @package mnc‚
 */
class GeoData {

	protected $zipcode = '';
	/**
	 * @var null | \WP_Query
	 */
	protected $data = null;
	protected $has_data = false;

	protected $radius = 10; // default = 10km
	protected $left = 0;


	/**
	 * GeoData constructor.
	 *
	 * @param string $zipcode
	 */
	public function __construct($zipcode) {
		$this->zipcode = $zipcode;
	}

	/**
	 * retrieves the lat long data from the mapping table
	 * @return bool false if no entry was found
	 */
	public function init() {
		global $wpdb;
		$zipcode    = $this->zipcode;
		$this->data = $wpdb->get_row( "SELECT latitude, longitude, `admin name1` as bundesland FROM wp_geodata WHERE `postal code`=$zipcode LIMIT 1" );
		if ( is_object( $this->data ) ) {
			$this->has_data = true;
		}
		return $this->has_data;
	}

	public function setRadius( $radius ) {
		$this->radius = $radius;
	}

	public function getLat() {
		return $this->data->latitude;
	}

	public function getLong() {
		return $this->data->longitude;
	}

	function isPLZInBundesland($bundesland) {
		if(!$this->data) {
			return false;
		}
		return $this->data->bundesland == $bundesland;
	}

}