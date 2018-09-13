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
 * IModelFileService provide service methods for file mapper.
 *
 * @since 1.0.0
 */
interface IModelFileService extends IModelMapperService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	/**
	 * Returns the model file associated with given file tag.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $fileTag
	 * @return \cmsgears\core\common\models\mappers\ModelFile
	 */
	public function getByFileTag( $parentId, $parentType, $fileTag );

	/**
	 * Search and returns the model files using file title.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $fileTitle
	 * @return \cmsgears\core\common\models\mappers\ModelFile[]
	 */
	public function searchByFileTitle( $parentId, $parentType, $fileTitle );

	/**
	 * Returns the model files using file type associated with given parent id and type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $fileType
	 * @return \cmsgears\core\common\models\mappers\ModelFile[]
	 */
	public function getByFileType( $parentId, $parentType, $fileType );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
