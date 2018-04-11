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

	public function getIdMetaMap( $model ) {

		return $this->metaService->getIdMetaMapByModelId( $model->id );
	}

	public function getMetaMapByMetaType( $model, $type ) {

		return $this->metaService->getNameMetaMapByType( $model->id, $type );
	}

	public function getMetaNameValueMapByMetaType( $model, $type ) {

		return $this->metaService->getNameValueMapByType( $model->id, $type );
	}

	// Read - Others ---

	// Create -------------

	// Update -------------

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
