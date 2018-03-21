<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\traits\resources;

// Yii Imports
use Yii;

/**
 * Used by services with base model having model meta trait.
 *
 * @since 1.0.0
 */
trait ModelMetaTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelMetaTrait ------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	public function getIdMetaMapByType( $model, $type ) {

		$modelMetaService = Yii::$app->factory->get( 'modelMetaService' );

		return $modelMetaService->getIdMetaMapByType( $model->id, static::$parentType, $type );
	}

	public function getNameMetaMapByType( $model, $type ) {

		$modelMetaService = Yii::$app->factory->get( 'modelMetaService' );

		return $modelMetaService->getNameMetaMapByType( $model->id, static::$parentType, $type );
	}

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateModelMetas( $model, $metas ) {

		$modelMetaService = Yii::$app->factory->get( 'modelMetaService' );

		foreach ( $metas as $meta ) {

			if( $meta->belongsTo( $model, self::$parentType ) ) {

				$modelMetaService->update( $meta );
			}
		}

		return true;
	}

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ModelMetaTrait ------------------------

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
