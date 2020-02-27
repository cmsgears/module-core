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
use cmsgears\core\common\services\interfaces\resources\IData;
use cmsgears\core\common\services\interfaces\mappers\IFile;

/**
 * IModelMessageService provide service methods for model comment.
 *
 * @since 1.0.0
 */
interface IModelMessageService extends IModelResourceService, IData, IFile {

	// Data Provider ------

	public function getPageByParent( $parentId, $parentType, $config = [] );

	public function getPageByBaseId( $baseId, $config = [] );

	// Read ---------------

	// Read - Models ---

	public function getByBaseId( $baseId, $config = [] );

	public function getByUser( $parentId, $parentType );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// States -----

	public function markNew( $model );

	public function markConsumed( $model );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
