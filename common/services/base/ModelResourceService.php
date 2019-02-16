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

	public function getPageByParent( $parentId, $parentType, $config = [] ) {

		$modelTable = $this->getModelTable();

		$config[ 'conditions'][ "$modelTable.parentId" ]	= $parentId;
		$config[ 'conditions'][ "$modelTable.parentType" ]	= $parentType;

		return $this->getPage( $config );
	}

	public function getPageByParentType( $parentType, $config = [] ) {

		$modelTable = $this->getModelTable();

		$config[ 'conditions'][ "$modelTable.parentType" ] = $parentType;

		return $this->getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public function getByParent( $parentId, $parentType, $config = [] ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByParent( $parentId, $parentType, $config );
	}

	public function getFirstByParent( $parentId, $parentType, $config = [] ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findFirstByParent( $parentId, $parentType, $config );
	}

	public function getByParentId( $parentId, $config = [] ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByParentId( $parentId, $config );
	}

	public function getByParentType( $parentType, $config = [] ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByParentType( $parentType, $config );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	public function deleteByParent( $parentId, $parentType ) {

		//$modelClass	= static::$modelClass;

		//return $modelClass::deleteByParent( $parentId, $parentType );

		$models	= $this->getByParent( $parentId, $parentType );

		foreach ( $models as $model ) {

			$this->delete( $model );
		}
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
