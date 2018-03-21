<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\interfaces\base;

/**
 * IName declare the methods provided by service trait - [[\cmsgears\core\common\services\traits\base\NameTrait]].
 *
 * @since 1.0.0
 */
interface IName {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByName( $name );

	public function searchByName( $name, $config = [] );

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
