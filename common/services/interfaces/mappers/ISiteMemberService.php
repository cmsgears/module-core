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
cmsgears\core\common\services\interfaces\base\IMapperService;

/**
 * ISiteMemberService provide service methods for site member mapper.
 *
 * @since 1.0.0
 */
interface ISiteMemberService extends IMapperService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function findBySiteIdUserId( $siteId, $userId );

	public function getSiteMemberBySiteId( $siteId );

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
