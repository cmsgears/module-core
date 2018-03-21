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
use cmsgears\core\common\services\interfaces\base\IEntityService;
use cmsgears\core\common\services\interfaces\base\IName;
use cmsgears\core\common\services\interfaces\base\ISlug;
use cmsgears\core\common\services\interfaces\resources\IData;

/**
 * ISiteService provide service methods for site model.
 *
 * @since 1.0.0
 */
interface ISiteService extends IEntityService, IData, IName, ISlug {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getIdMetaMapBySlug( $slug );

	public function getMetaMapBySlugType( $slug, $type );

	public function getMetaNameValueMapBySlugType( $slug, $type );

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
