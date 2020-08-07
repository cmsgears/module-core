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
use cmsgears\core\common\services\interfaces\base\IMultiSite;
use cmsgears\core\common\services\interfaces\base\IResourceService;
use cmsgears\core\common\services\interfaces\base\IShared;
use cmsgears\core\common\services\interfaces\base\IVisibility;
use cmsgears\core\common\services\interfaces\cache\IGridCacheable;
use cmsgears\core\common\services\interfaces\resources\IData;

/**
 * IFileService provide service methods for file model.
 *
 * @since 1.0.0
 */
interface IFileService extends IResourceService, IData, IGridCacheable, IMultiSite, IShared, IVisibility {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateData( $model, $config = [] );

	public function saveImage( $file, $args = [] );

	public function saveFile( $file, $args = [] );

	public function saveFiles( $model, $files = [] );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
