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
 * IModelOptionService provide service methods for option mapper.
 *
 * @since 1.0.0
 */
interface IModelOptionService extends IModelMapperService {

	// Data Provider ------

	// Read ---------------

	public function getModelCounts( $parentType, $categorySlug );

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function toggle( $parentId, $parentType, $modelId );

	public function bindOptions( $binder, $parentType );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
