<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

/**
 * CmgEntity Entity
 * It's the parent entity for all the CMSGears based entities and provide the common methods to be utilised by all the entities.
 */
abstract class CmgEntity extends ActiveRecord {

	public $traitParams	= [];

	// Instance Methods --------------------------------------------

	// Check whether model already exist	
	public function isExisting() {

		return isset( $this->id ) && $this->id > 0;
	}

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
	 * Returns row count for the model
	 */
	public static function getCount( $conditions = [] ) {

		return self::find()->where( $conditions )->count();
	}

	// Default Searching - Useful for id based models

	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}
}

?>