<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\interfaces\mappers;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IModelMapperService;

/**
 * IModelCategoryService provide service methods for category mapper.
 *
 * @since 1.0.0
 */
interface IModelCategoryService extends IModelMapperService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getModelCounts( $parentType, $categoryType );

	// Read - Lists ----

	public function getActiveCategoryIdList( $categoryId, $parentType );

	public function getActiveCategoryIdListByParent( $parentId, $parentType );

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function toggle( $parentId, $parentType, $modelId );

	public function bindCategories( $parentId, $parentType, $config = [] );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
