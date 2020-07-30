<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\resources;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\interfaces\resources\IModelHierarchyService;

/**
 * ModelHierarchyService provide service methods of model hierarchy.
 *
 * @since 1.0.0
 */
class ModelHierarchyService extends \cmsgears\core\common\services\base\ModelResourceService implements IModelHierarchyService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\resources\ModelHierarchy';

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

	// ModelHierarchyService -----------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getRoot( $rootId, $parentType ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findRoot( $rootId, $parentType );
	}

	public function getChildren( $parentId, $parentType ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByParent( $parentId, $parentType );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createInHierarchy( $parentId, $parentType, $rootId, $childId ) {

		$root = $this->getRoot( $rootId, $parentType );

		// Add Root
		if( !isset( $root ) ) {

			$model = $this->getModelObject();

			$model->rootId		= $rootId;
			$model->parentType	= $parentType;
			$model->lValue		= CoreGlobal::HIERARCHY_VALUE_L;
			$model->rValue		= CoreGlobal::HIERARCHY_VALUE_R;

			// Create Model
			$model->save();
		}

		$model	= $this->getModelObject();

		$model->rootId		= $rootId;
		$model->parentType	= $parentType;

		$model->parentId	= $parentId;
		$model->childId		= $childId;

		// Create Model
		$model->save();
	}

	public function assignRootChildren( $parentType, $binder ) {

		$modelClass	= static::$modelClass;

		$parentId	= $binder->binderId;
		$binded		= $binder->binded;

		// Add root children if not exist in hierarchy
		foreach( $binded as $id ) {

			$child = $modelClass::findChild( $parentId, $parentType, $id );

			if( !isset( $child ) ) {

				$this->createInHierarchy( $parentId, $parentType, $parentId, $id );
			}
		}

		// Remove unmapped children
		$existingChildren = $this->getChildren( $parentId, $parentType );

		foreach( $existingChildren as $child ) {

			// Remove unmapped child
			if( !in_array( $child->childId, $binded ) ) {

				// TODO: Use delete in hierarchy method to maintain the parent child hierarchy
				$child->delete();
			}
		}
	}

	// Update -------------

	// Delete -------------

	public function deleteByRootId( $rootId, $parentType ) {

		$modelClass	= static::$modelClass;

		$modelClass::deleteByRootId( $rootId, $parentType );
	}

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ModelHierarchyService -----------------

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
