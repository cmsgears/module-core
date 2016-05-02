<?php
namespace cmsgears\core\common\services\mappers;

// Yii Imports
use \Yii;
use yii\db\Query;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\mappers\ModelHierarchy;

/**
 * The class ModelHierarchyService is base class to perform database activities for ModelHierarchy Entity.
 */
class ModelHierarchyService extends \cmsgears\core\common\services\base\Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function findRoot( $rootId, $parentType ) {

		ModelHierarchy::findRoot( $rootId, $parentType );
	}

	public static function findChildrent( $parentId, $parentType ) {

		ModelHierarchy::findByParent( $parentId, $parentType );
	}

	// Create --------------

	public static function create( $parentId, $parentType, $rootId, $childId ) {

		$modelHierarchy				= new ModelHierarchy();

		$modelHierarchy->parentId	= $parentId;
		$modelHierarchy->childId	= $childId;
		$modelHierarchy->rootId		= $rootId;
		$modelHierarchy->parentType	= $parentType;

		$modelHierarchy->save();
	}

	public static function createInHierarchy( $parentId, $parentType, $rootId, $childId ) {

		$root	= self::findRoot( $rootId, $parentType );

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
		$model->childId	= $childId;
	}

	public static function assignChildren( $parentType, $binder ) {

		$parentId	= $binder->binderId;
		$children	= $binder->bindedData;

		// Insert topmost parent with immediate children

		foreach ( $children as $childId ) {

			self::createInHierarchy( $parentId, $parentType, $parentId, $childId );
		}
	}

	// Update --------------

	// Delete --------------
}

?>