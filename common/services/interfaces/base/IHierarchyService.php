<?php
namespace cmsgears\core\common\services\interfaces\base;

interface IHierarchyService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public function getChildNodes( $parentId, $conditions = [] );

	public function getLeafNodes( $rootId, $conditions = [] );

	public function getParentNodes( $leafId, $conditions = [] );

    // Read - Lists ----

	public function getLevelList( $conditions = [] );

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

?>