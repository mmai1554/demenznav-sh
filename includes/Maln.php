<?php

namespace mnc;

/**
 * Class MIHTML
 * HTML components for everday use
 * meant for small reusable staff
 * and as router for Render... Components:
 * @package App\Http\LayoutComponents
 */
abstract class Maln {

	public static function icon_li( $content, $icon ) {
		$a   = [];
		$a[] = '<li>';
		$a[] = '<div class="icon-wrap">';
		$a[] = '<span class="fl-icon">';
		$a[] = '<i class="ua-icon ' . $icon . '"></i>';
		$a[] = '</span>';
		$a[] = '<div class="fl-icon-text">';
		$a[] = $content;
		$a[] = '</div>';
		$a[] = '</div>';
		$a[] = '</li>';

		return implode( "", $a );
	}


	/**
	 * @param $html
	 * @param string $class
	 * @param string $style
	 * @param string $id
	 * @param array $dataattributes
	 *
	 * @return string
	 */
	public static function div( $html, $class = '', $style = '', $id = '', $dataattributes = [] ) {
		return sprintf(
			'<div%s%s%s%s>%s</div>',
			self::id( $id ),
			self::style( $style ),
			self::getClass( $class ),
			self::getDataAttribute( $dataattributes ),
			$html
		);
	}

	/**
	 * @param $html
	 * @param string $class
	 * @param string $style
	 * @param string $id
	 *
	 * @return string
	 */
	public static function li( $html, $class = '', $style = '', $id = '' ) {
		return sprintf(
			'<li%s%s%s>%s</li>',
			self::id( $id ),
			self::style( $style ),
			self::getClass( $class ),
			$html
		);
	}

	/**
	 * @param array $arr
	 *
	 * @return string
	 */
	public static function atos( array $arr ) {
		return implode( "\n", $arr );
	}

	public static function ul( $arr, $class = '', $style = '', $id = '' ) {
		$html   = [];
		$html[] = self::ul_open( $class, $style, $id );
		foreach ( $arr as $item ) {
			$html[] = self::li( $item );
		}
		$html[] = self::ul_close();

		return self::atos( $arr );
	}

	/**
	 * @param string $class
	 * @param string $style
	 * @param string $id
	 *
	 * @return string
	 */
	public static function ul_open( $class = '', $style = '', $id = '' ) {
		return sprintf(
			'<ul%s%s%s>',
			self::id( $id ),
			self::style( $style ),
			self::getClass( $class )
		);
	}

	/**
	 * @return string
	 */
	public static function ul_close() {
		return '</ul>';
	}

	public static function th( $html, $class = '', $style = '', $id = '', $dataattributes = [] ) {
		return sprintf(
			'<th%s%s%s%s>%s</th>',
			self::id( $id ),
			self::style( $style ),
			self::getClass( $class ),
			self::getDataAttribute( $dataattributes ),
			$html
		);
	}


	private static function id( $id ) {
		return strlen( $id ) ? ' id="' . $id . '"' : '';
	}

	private static function style( $style = '' ) {
		return strlen( $style ) ? ' style="' . $style . '"' : '';
	}

	private static function getClass( $class = '' ) {
		return strlen( $class ) ? ' class="' . $class . '"' : '';
	}

	private static function getDataAttribute( $arrData = [] ) {
		$arrMap = [];
		if ( count( $arrData ) == 0 ) {
			return '';
		} else {
			foreach ( $arrData as $row ) {
				foreach ( $row as $key => $data ) {
					$key      = 'data-' . $key;
					$arrMap[] = ( $key . '="' . $data . '"' );
				}
			}
		}

		return implode( ' ', $arrMap );
	}


}