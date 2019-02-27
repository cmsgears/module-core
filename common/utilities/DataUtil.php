<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\utilities;

class DataUtil {

	// Static Methods ----------------------------------------------

	public static function generateNumMap( $min, $max, $interval = 1, $text = false ) {

		$map = [];

		for( $i = $min; $i <= $max; $i += $interval ) {

			$map[ $i ] = $i;
		}

		return $map;
	}

	/**
	 * The method sort give object array using the attribute having number value. The array objects will be sorted in ascending order by default.
	 * It expects that all the array elements must have the given attribute.
	 */
	public static function sortObjectArrayByNumber( $array, $attribute, $asc = true ) {

		uasort( $array, function( $item1, $item2 ) use ( $attribute, $asc ) {

			if( $asc ) {

				return $item1->$attribute > $item2->$attribute;
			}
			else {

				return $item1->$attribute < $item2->$attribute;
			}
		});

		return $array;
	}
}
