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
 * IFeatured declare the methods provided by status trait - [[\cmsgears\core\common\services\traits\base\StatusTrait]].
 *
 * @since 1.0.0
 */
interface IStatus {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	/**
	 * Triggers status change notification on change in status.
	 *
	 * @param array $config
	 * @return array
	 */
	public function checkStatusChange( $model, $oldStatus, $config = [] );

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
