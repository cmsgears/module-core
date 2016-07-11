<?php
namespace cmsgears\core\common\services\interfaces\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface IModelHierarchyService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public function getRoot( $rootId, $parentType );

	public function getChildren( $parentId, $parentType );

    // Read - Lists ----

    // Read - Maps -----

	// Create -------------

	public function createInHierarchy( $parentId, $parentType, $rootId, $childId );

	public function assignChildren( $parentType, $binder );

	// Update -------------

	// Delete -------------

}