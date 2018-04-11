<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\interfaces\hierarchy;

/**
 * INestedSet provide service methods for models using nested set for hierarchy.
 *
 * @since 1.0.0
 */
interface INestedSet extends IHierarchy {

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

	public function updateInHierarchy( $model );

	// Delete -------------

	public function deleteInHierarchy( $model );

	public function deleteAllInHierarchy( $model );

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
