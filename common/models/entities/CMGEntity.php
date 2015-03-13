<?php
namespace cmsgears\modules\core\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

class CMGEntity extends ActiveRecord {

	// Static Methods --------------------------------------------

	public static function generatSearchQuery( $field, $searchTerms ) {

		$searchTerms	= preg_split( '/,/', $searchTerms );
		$searchQuery	= "";

		foreach ( $searchTerms as $key => $value ) {

			if( $key  == 0 ) {

				$searchQuery .= "$field like '%$value%'";
			}
			else {

				$searchQuery .= " or $field like '%$value%'";
			}				
		}
		
		return $searchQuery;
	}
}

?>