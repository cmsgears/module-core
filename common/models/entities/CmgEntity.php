<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

class CmgEntity extends ActiveRecord {

	// Instance Methods --------------------------------------------

	/**
	 * The method allows to update a model for selected columns to target model.
	 */
	public function copyForUpdateTo( $toModel, $attributes = [] ) {

		foreach ( $attributes as $attribute ) {

			$toModel->setAttribute( $attribute, $this->getAttribute( $attribute ) ); 
		}
	}

	/**
	 * The method allows to update a model for selected columns from target model.
	 */
	public function copyForUpdateFrom( $fromModel, $attributes = [] ) {

		foreach ( $attributes as $attribute ) {

			$this->setAttribute( $attribute, $fromModel->getAttribute( $attribute ) ); 
		}
	}

	// Static Methods --------------------------------------------
	
	/**
	 * It generate search query from columns by parsing the comma seperated string. 
	 */
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