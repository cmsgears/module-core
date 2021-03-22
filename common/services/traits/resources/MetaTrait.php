<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\traits\resources;

/**
 * Used by services with base model having meta table.
 *
 * @since 1.0.0
 */
trait MetaTrait {

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

	public function getMetaIdMetaMap( $model ) {

		return $this->metaService->getIdMetaMapByModelId( $model->id );
	}

	public function getMetaNameMetaMapByType( $model, $type ) {

		return $this->metaService->getNameMetaMapByType( $model->id, $type );
	}

	public function getMetaNameValueMapByType( $model, $type ) {

		return $this->metaService->getNameValueMapByType( $model->id, $type );
	}

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateMetas( $model, $metas, $metaService ) {

		foreach( $metas as $meta ) {

			if( $meta->belongsTo( $model ) ) {

				$metaService->update( $meta );
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
