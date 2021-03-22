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
use cmsgears\core\common\services\interfaces\base\IMapperService;
use cmsgears\core\common\services\interfaces\base\IFeatured;

/**
 * ISiteMemberService provide service methods for site member mapper.
 *
 * @since 1.0.0
 */
interface ISiteMemberService extends IMapperService, IFeatured {

	// Data Provider ------

	public function getPageBySiteId( $siteId, $config = [] );

	// Read ---------------

	// Read - Models ---

	public function getBySiteIdUserId( $siteId, $userId );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	public static function deleteBySiteId( $siteId );

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
