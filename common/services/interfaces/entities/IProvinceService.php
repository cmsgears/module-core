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

/**
 * IProvinceService provide service methods for province model.
 *
 * @since 1.0.0
 */
interface IProvinceService extends IEntityService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByCountryId( $countryId );

	public function getByCountryIdCode( $countryId, $code );

	public function getByCountryIdIso( $countryId, $iso );

	// Read - Lists ----

	public function getListByCountryId( $countryId );

	// Read - Maps -----

	public function getMapByCountryId( $countryId, $config = [] );

	public function getIsoNameMapByCountryId( $countryId );

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
