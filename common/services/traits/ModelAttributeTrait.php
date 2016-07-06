<?php
namespace cmsgears\core\common\services\traits;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Used by services with base model having attributes trait.
 */
trait ModelAttributeTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelAttributeTrait -------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	public function getAttributeMapByType( $model, $type ) {

		return self::findAttributeMapByType( $model, $type );
	}

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateAttributes( $attributes ) {

		foreach ( $attributes as $attribute ) {

			$this->modelAttributeService->update( $attribute );
		}

		return true;
	}

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ModelAttributeTrait -------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	public static function findAttributeMapByType( $model, $type ) {

		return ModelAttributeService::findMapByType( $model->id, static::$modelType, $type );
	}

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
