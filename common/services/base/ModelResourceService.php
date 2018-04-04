<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\base;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IModelResourceService;

/**
 * ModelResourceService defines commonly used methods specific to model resources.
 *
 * @since 1.0.0
 */
abstract class ModelResourceService extends ResourceService implements IModelResourceService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelResourceService ------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByParent( $parentId, $parentType ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findByParent( $parentId, $parentType );
	}

	public function getByParentId( $parentId ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findByParentId( $parentId );
	}

	public function getByParentType( $parentType ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findByParentType( $parentType );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	public function deleteByParent( $parentId, $parentType ) {

		$modelClass	= self::$modelClass;

		return $modelClass::deleteByParent( $parentId, $parentType );

		/*
		$models	= $this->getByParent( $parentId, $parentType );

		foreach ( $models as $model ) {

			$this->delete( $model );
		}
		 */
	}

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ModelResourceService ------------------

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