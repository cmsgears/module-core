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

	public function getIdAttributeMapByType( $model, $type ) {

		$modelAttributeService = Yii::$app->factory->get( 'modelAttributeService' );

		return $modelAttributeService->getIdObjectMapByType( $model->id, static::$parentType, $type );
	}

	public function getNameAttributeMapByType( $model, $type ) {

		$modelAttributeService = Yii::$app->factory->get( 'modelAttributeService' );

		return $modelAttributeService->getNameObjectMapByType( $model->id, static::$parentType, $type );
	}

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateModelAttributes( $model, $attributes ) {

		$modelAttributeService = Yii::$app->factory->get( 'modelAttributeService' );

		foreach ( $attributes as $attribute ) {

			if( $model->id == $attribute->parentId ) {

				$modelAttributeService->update( $attribute );
			}
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

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
