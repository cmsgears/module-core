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
use cmsgears\core\common\services\interfaces\base\IResourceService;
use cmsgears\core\common\services\interfaces\resources\IData;

/**
 * IOptionService provide service methods for option model.
 *
 * @since 1.0.0
 */
interface IOptionService extends IResourceService, IData {

	// Data Provider ------

	public function getPageByCategoryId( $categoryId, $config = [] );

	// Read ---------------

	// Read - Models ---

	public function getByCategoryId( $categoryId );

	public function getByNameCategoryId( $name, $categoryId );

	public function isExistByNameCategoryId( $name, $categoryId );

	// Read - Lists ----

	public function getIdListByCategoryId( $categoryId, $config = [] );

	public function searchByNameCategoryId( $name, $categoryId, $config = [] );

	// Read - Maps -----

	public function getIdNameMapByCategoryId( $categoryId, $config = [] );

	public function getActiveIdNameMapByCategoryId( $categoryId, $config = [] );

	public function getValueNameMapByCategoryId( $categoryId, $config = [] );

	public function getActiveValueNameMapByCategoryId( $categoryId, $config = [] );

	public function getIdNameMapByCategorySlug( $slug, $config = [] );

	public function getActiveIdNameMapByCategorySlug( $slug, $config = [] );

	public function getValueNameMapByCategorySlug( $slug, $config = [] );

	public function getActiveValueNameMapByCategorySlug( $slug, $config = [] );

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	public function deleteByCategoryId( $categoryId );

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
