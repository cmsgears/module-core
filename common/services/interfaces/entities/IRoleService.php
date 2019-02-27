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
use cmsgears\core\common\services\interfaces\base\INameType;
use cmsgears\core\common\services\interfaces\base\ISlugType;
use cmsgears\core\common\services\interfaces\resources\IData;

/**
 * IRoleService provide service methods for role model.
 *
 * @since 1.0.0
 */
interface IRoleService extends IEntityService, IData, INameType, ISlugType {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	public function getIdNameListByTypeGroup( $type, $group = false, $config = [] );

	// Read - Maps -----

	public function getIdNameMapByRoles( $roles );

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function bindPermissions( $binder );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
