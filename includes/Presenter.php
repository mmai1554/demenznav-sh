<?php

namespace mnc;

class Presenter {

	/**
	 * @return Presenter
	 */
	public static function init() {
		return new self;
	}

	public function __construct() {

	}

	public function __call( $name, $arguments ) {
		return get_field( $name, $arguments[0] );
	}

	/**
	 * builds the complete address field from the given fields
	 *
	 * @param $post_id
	 *
	 * @return string
	 */
	function adresse( $post_id ) {
		$str = get_field( 'strasse', $post_id );
		$a   = [];
		$a[] = $str;
		$a[] = get_field( 'plz', $post_id ) . '&nbsp;' . get_field( 'ort', $post_id );
		$a[] = get_field( 'adresse_zusatz', $post_id );

		return implode( '<br>', $a );
	}

	function plzort( $post_id ) {
		return get_field( 'plz', $post_id ) . '&nbsp;' . get_field( 'ort', $post_id );
	}

	/**
	 * @param $index
	 *
	 * @return mixed|string
	 */
	function getLetterByIndex( $index ) {
		$abc = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		if ( $index < 0 ) {
			return 'A';
		}
		if ( $index > 26 ) {
			return 'Z';
		}

		return $abc[ $index ];
	}

	function getNextLetter( $letter ) {
		$abc = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		if ( $letter == '' ) {
			return 'A';
		}
		if ( strpos( $abc, $letter ) >= 0 && strpos( $abc, $letter ) <= strlen( $abc ) ) {
			return $abc[ strpos( $abc, $letter ) + 1 ];
		} else {
			return 'A';
		}
	}


}