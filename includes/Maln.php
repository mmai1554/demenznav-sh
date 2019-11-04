<?php

namespace mnc;

/**
 * Class Maln = Umgangssprache fuer "Zeichne ein ..."
 * HTML components for everday use
 * meant for small reusable staff
 * and as router for Render... Components:
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

	public static function icon_li_material( $content, $icon ) {
		$a   = [];
		$a[] = '<li>';
		$a[] = '<div class="icon-wrap">';
		$a[] = '<span class="fl-icon">';
		$a[] = '<i class="material-icons mnc-iconlist">' . $icon . '</i>';
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
	 * @param string $url
	 * @param string $html
	 * @param string $target
	 * @param string $title
	 * @param string $class
	 * @param string $style
	 * @param string $id
	 * @param array $dataattributes
	 *
	 * @return string
	 */
	public static function alink( $url, $html, $target, $title, $class = '', $style = '', $id = '', $dataattributes = [] ) {
		return sprintf(
			'<a href="%s"%s%s%s%s%s%s>%s</a>',
			$url,
			self::id( $id ),
			self::target( $target ),
			self::title( $title ),
			self::getClass( $class ),
			self::style( $style ),
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

	private static function target( $target ) {
		if ( $target == '_new' ) {
			$target = '_blank';
		}

		return strlen( $target ) ? ' target="' . $target . '"' : '';
	}

	private static function style( $style = '' ) {
		return strlen( $style ) ? ' style="' . $style . '"' : '';
	}

	private static function title( $title = '' ) {
		return strlen( $title ) ? ' title="' . $title . '"' : '';
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