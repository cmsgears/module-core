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

/**
 * ICityService provide service methods for city model.
 *
 * @since 1.0.0
 */
interface ICityService extends IEntityService, IName {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getUnique( $name, $countryId, $provinceId );

	public function isUniqueExist( $name, $countryId, $provinceId );

	public function getUniqueByZone( $name, $countryId, $provinceId, $zone );

	public function isUniqueExistByZone( $name, $countryId, $provinceId, $zone );

	public function getUniqueByRegionId( $name, $countryId, $provinceId, $regionId );

	public function isUniqueExistByRegionId( $name, $countryId, $provinceId, $regionId );

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
