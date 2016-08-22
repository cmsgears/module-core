<?php
namespace cmsgears\core\common\services\interfaces\hierarchy;

interface INestedSetService extends IHierarchyService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public function getChildNodes( $parentId, $conditions = [] );

	public function getLeafNodes( $rootId, $conditions = [] );

	public function getParentNodes( $leafId, $conditions = [] );

    // Read - Lists ----

	public function getLevelList( $config = [] );

	public function getSubLevelList( $parentId, $rootId, $config = [] );

	public function getSubLevelIdList( $parentId, $rootId, $config = [] );

	public function getChildIdListById( $categoryId );

	public function getChildIdList( $category );

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createInHierarchy( $model );

	// Update -------------

	public function updateInHierarchy( $model, $modelToUpdate );

	// Delete -------------

	public function deleteInHierarchy( $model );

	public function deleteAllInHierarchy( $model );
}
