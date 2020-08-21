<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\utilities;

class UrlUtil {

	public static function isAbsolutePath( $path, $alias = true ) {

		if( $alias ) {

			return $path[ 0 ] === '@';
		}

		return $path[ 1 ] === ':' || $path[ 0 ] === '/';
	}

	public static function normalizePath( $path ) {

		$parts	= array();// Array to build a new path from the good parts
		$path	= str_replace( '\\', '/', $path );// Replace backslashes with forwardslashes
		$path	= preg_replace( '/\/+/', '/', $path );// Combine multiple slashes into a single slash

		$segments = explode( '/', $path );// Collect path segments

		$test = '';// Initialize testing variable

		foreach( $segments as $segment ) {

			if( $segment != '.' ) {

				$test = array_pop( $parts );

				if( is_null( $test ) ) {

					$parts[] = $segment;
				}
				else if( $segment == '..' ) {

					if( $test == '..' ) {

						$parts[] = $test;
					}

					if( $test == '..' || $test == '' ) {

						$parts[] = $segment;
					}
				}
				else {

					$parts[] = $test;
					$parts[] = $segment;
				}
			}
		}

		return implode( '/', $parts );
	}

	public static function getSiteProtocol() {

		//$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';
		$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/')));

		return $protocol;
	}

	public static function addParam( $url, $key, $value ) {

		$query = parse_url( $url, PHP_URL_QUERY );

		if( $query ) {

			$url .= "&{$key}=" . urlencode( $value );
		}
		else {

			$url .= "?{$key}=" . urlencode( $value );
		}

		return $url;
	}

}
