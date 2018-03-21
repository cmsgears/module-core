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
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\interfaces\base\IResourceService;
use cmsgears\core\common\services\interfaces\resources\IData;

/**
 * IOptionService provide service methods for option model.
 *
 * @since 1.0.0
 */
interface IOptionService extends IResourceService, IData {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByCategoryId( $categoryId );

	public function getByCategorySlug( $categorySlug, $categoryType = CoreGlobal::TYPE_OPTION_GROUP );

	public function getByNameCategoryId( $name, $categoryId );

	public function getByNameCategoryName( $name, $categoryName, $categoryType = CoreGlobal::TYPE_OPTION_GROUP );

	public function getByValueCategoryName( $value, $categoryName, $categoryType = CoreGlobal::TYPE_OPTION_GROUP );

	// Read - Lists ----

	public function getIdListByCategoryId( $categoryId, $config = [] );

	// Read - Maps -----

	public function getIdNameMapByCategoryId( $categoryId, $config = [] );

	public function getIdNameMapByCategorySlug( $categoryName, $config = [], $type = CoreGlobal::TYPE_OPTION_GROUP );

	public function getValueNameMapByCategoryId( $categoryId );

	public function getValueNameMapByCategoryName( $categoryName, $type = CoreGlobal::TYPE_OPTION_GROUP );

	public function getValueNameMapByCategorySlug( $categorySlug, $type = CoreGlobal::TYPE_OPTION_GROUP );

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
