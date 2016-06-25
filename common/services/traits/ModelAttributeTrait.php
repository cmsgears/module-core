<?php
namespace cmsgears\core\common\services\traits;

/**
 * Used by services with base model having attributes trait.
 */
trait ModelAttributeTrait {

	// Instance Methods --------------------------------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	// Static Methods ----------------------------------------------

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

	public static function updateAttributes( $model, $attributes ) {

		foreach ( $attributes as $attribute ) {

			ModelAttributeService::update( $attribute );
		}

		return true;
	}

	// Delete -------------
}

?>