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

		ModelHierarchy::findRoot( $rootId, $parentType );
	}

	public function getChildren( $parentId, $parentType ) {

		ModelHierarchy::findByParent( $parentId, $parentType );
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

	public function assignChildren( $parentType, $binder ) {

		$parentId	= $binder->binderId;
		$children	= $binder->bindedData;

		// Insert topmost parent with immediate children

		foreach ( $children as $childId ) {

			$this->createInHierarchy( $parentId, $parentType, $parentId, $childId );
		}
	}

	// Update -------------

	// Delete -------------

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
