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
cmsgears\core\common\services\interfaces\base\IModelMapperService;

/**
 * IModelFileService provide service methods for file mapper.
 *
 * @since 1.0.0
 */
interface IModelFileService extends IModelMapperService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByFileTitle( $parentId, $parentType, $fileTitle );

	public function getByFileTitleLike( $parentId, $parentType, $likeTitle );

	public function getByFileType( $parentId, $parentType, $fileType );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createOrUpdateByTitle( $file, $config = [] );

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
