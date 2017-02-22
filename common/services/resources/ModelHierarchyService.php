<?php
namespace cmsgears\core\common\services\resources;

// Yii Imports
use \Yii;
use yii\db\Query;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\ModelHierarchy;

use cmsgears\core\common\services\interfaces\resources\IModelHierarchyService;

/**
 * The class ModelHierarchyService is base class to perform database activities for ModelHierarchy Entity.
 */
class ModelHierarchyService extends \cmsgears\core\common\services\base\EntityService implements IModelHierarchyService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\resources\ModelHierarchy';

	public static $modelTable	= CoreTables::TABLE_MODEL_HIERARCHY;

	public static $parentType	= null;

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

		return ModelHierarchy::findRoot( $rootId, $parentType );
	}

	public function getChildren( $parentId, $parentType ) {

		return ModelHierarchy::findByParent( $parentId, $parentType );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createInHierarchy( $parentId, $parentType, $rootId, $childId ) {

		$root	= $this->getRoot( $rootId, $parentType );

		// Add Root
		if( !isset( $root ) ) {

			$model				= new ModelHierarchy();
			$model->rootId		= $rootId;
			$model->parentType	= $parentType;
			$model->lValue		= CoreGlobal::HIERARCHY_VALUE_L;
			$model->rValue		= CoreGlobal::HIERARCHY_VALUE_R;

			// Create Model
			$model->save();
		}

		$model				= new ModelHierarchy();
		$model->rootId		= $rootId;
		$model->parentType	= $parentType;

		$model->parentId	= $parentId;
		$model->childId		= $childId;

		// Create Model
		$model->save();
	}

	public function assignRootChildren( $parentType, $binder ) {

		$parentId		= $binder->binderId;
		$childrenIdList	= $binder->bindedData;

		// Add root children if not exist in hierarchy
		foreach ( $childrenIdList as $childId ) {

			$child	= ModelHierarchy::findChild( $parentId, $parentType, $childId );

			if( !isset( $child ) ) {

				$this->createInHierarchy( $parentId, $parentType, $parentId, $childId );
			}
		}

		// Remove unmapped children
		$existingChildren	= $this->getChildren( $parentId, $parentType );

		foreach ( $existingChildren as $child ) {

			// Remove unmapped child
			if( !in_array( $child->childId, $childrenIdList ) ) {

				// TODO: Use delete in hierarchy method to maintain the parent child hierarchy
				$child->delete();
			}
		}
	}

	// Update -------------

	// Delete -------------

	public function deleteByRootId( $rootId, $parentType ) {

		ModelHierarchy::deleteByRootId( $rootId, $parentType );
	}

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
