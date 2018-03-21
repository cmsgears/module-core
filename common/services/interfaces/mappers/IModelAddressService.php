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
cmsgears\core\common\services\interfaces\base\IModelMapperService;

/**
 * IModelAddressService provide service methods for address mapper.
 *
 * @since 1.0.0
 */
interface IModelAddressService extends IModelMapperService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByType( $parentId, $parentType, $type, $first = false );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createOrUpdate( $address, $config = [] ) ;

	public function createOrUpdateByType( $address, $config = [] );

	public function createShipping( $address, $config = [] );

	public function copyToShipping( $address, $config = [] );

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
