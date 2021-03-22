<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\traits\base;

/**
 * VisibilityTrait provide methods for models using visibility trait.
 *
 * @since 1.0.0
 */
trait VisibilityTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// VisibilityTrait -----------------------

	// Data Provider ------

	public function getPageByVisibility( $visibility, $config = [] ) {

		$modelTable	= static::$modelTable;

		$config[ 'conditions' ][ "$modelTable.visibility" ]	= $visibility;

		return $this->getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateVisibility( $model, $visibility ) {

		$model->visibility = $visibility;

		$model->update();

		return $model;
	}

	public function makePublic( $model ) {

		return $this->updateVisibility( $model, IVisibility::VISIBILITY_PUBLIC );
	}

	public function makeProtected( $model ) {

		return $this->updateVisibility( $model, IVisibility::VISIBILITY_PROTECTED );
	}

	public function makePrivate( $model ) {

		return $this->updateVisibility( $model, IVisibility::VISIBILITY_PRIVATE );
	}

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// VisibilityTrait -----------------------

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
