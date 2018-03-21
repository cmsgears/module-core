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
use cmsgears\core\common\services\interfaces\base\INameType;
use cmsgears\core\common\services\interfaces\base\ISlugType;
use cmsgears\core\common\services\interfaces\base\IResourceService;
use cmsgears\core\common\services\interfaces\hierarchy\INestedSet;
use cmsgears\core\common\services\interfaces\resources\IData;

/**
 * ICategoryService provide service methods for category model.
 *
 * @since 1.0.0
 */
interface ICategoryService extends IResourceService, IData, INameType, INestedSet, ISlugType {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByParentId( $id );

	public function getFeaturedByType( $type );

	public function getL0ByType( $type );

	// Read - Lists ----

	public function getTopLevelIdNameListByType( $type, $config = [] );

	public function getTopLevelIdNameListById( $id, $config = [] );

	public function getLevelListByType( $type );

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function markFeatured( $model );

	public function markRegular( $model );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
