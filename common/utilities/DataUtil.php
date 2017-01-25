<?php
namespace cmsgears\core\common\utilities;

use \Yii;
use yii\helpers\Html;

class DataUtil {

	// Static Methods ----------------------------------------------

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

?>