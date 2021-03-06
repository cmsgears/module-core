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
 * IModelTagService provide service methods for tag mapper.
 *
 * @since 1.0.0
 */
interface IModelTagService extends IModelMapperService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createFromArray( $parentId, $parentType, $tags );

	public function createFromCsv( $parentId, $parentType, $tags );

	public function createFromJson( $parentId, $parentType, $tags );

	// Update -------------

	public function bindTags( $parentId, $parentType, $config = [] );

	// Delete -------------

	public function deleteByTagSlug( $parentId, $parentType, $tagSlug, $delete = false );

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
