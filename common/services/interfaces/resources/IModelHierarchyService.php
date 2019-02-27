<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\interfaces\resources;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IModelResourceService;

/**
 * IModelHierarchyService provide service methods for model hierarchy.
 *
 * @since 1.0.0
 */
interface IModelHierarchyService extends  IModelResourceService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getRoot( $rootId, $parentType );

	public function getChildren( $parentId, $parentType );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createInHierarchy( $parentId, $parentType, $rootId, $childId );

	public function assignRootChildren( $parentType, $binder );

	// Update -------------

	// Delete -------------

	public function deleteByRootId( $rootId, $parentType );

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
