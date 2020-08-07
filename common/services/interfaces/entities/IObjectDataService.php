<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\interfaces\entities;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IApproval;
use cmsgears\core\common\services\interfaces\base\IFeatured;
use cmsgears\core\common\services\interfaces\base\IEntityService;
use cmsgears\core\common\services\interfaces\base\IMultiSite;
use cmsgears\core\common\services\interfaces\base\INameType;
use cmsgears\core\common\services\interfaces\base\IShared;
use cmsgears\core\common\services\interfaces\base\ISlugType;
use cmsgears\core\common\services\interfaces\cache\IGridCacheable;
use cmsgears\core\common\services\interfaces\resources\IData;

/**
 * IObjectDataService provide service methods for object model.
 *
 * @since 1.0.0
 */
interface IObjectDataService extends IEntityService, IApproval, IData, IFeatured, IGridCacheable,
	IMultiSite, INameType, IShared, ISlugType {

	// Data Provider ------

	/*
	 * Returns the page by parent id.
	 *
	 * @param integer $parentId
	 */
	public function getPageByParentId( $parentId, $config = [] );

	/**
	 * Returns the page by mapped object.
	 *
	 * @param string $type
	 * @param integer $parentId
	 * @param string $parentType
	 * @param array $config
	 */
	public function getPageByTypeParent( $type, $parentId, $parentType, $config = [] );

	// Read ---------------

	// Read - Models ---

	public function getActive( $config = [] );

	public function getActiveByType( $type, $config = [] );

	public function getL0ByType( $type, $config = [] );

	public function getByParentId( $parentId, $config = [] );

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
