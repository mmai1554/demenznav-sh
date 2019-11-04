<?php

namespace mnc;

class Presenter {

	public static function init()
	{
		new self;
	}

	function __construct() {
		add_action( 'format_website', array ( $this, 'website' ), 10, 1 );
	}

	/**
	 * returns a formatted website link
	 * use only on website fields
	 * true: echos the formatted value
	 * false: returns the formatted value as string
	 *
	 * @param $fieldname
	 *
	 * @return string
	 */
	function website( $fieldname ) {
		$url = get_field( $fieldname, false, false );
		if ( ! $url ) {
			return '';
		}

		return sprintf( '<a href="%s" target="_blank" title="Website %s in neuem Fenster öffnen...">%s</a>',
			$url, $url, $url
		);
	}


}