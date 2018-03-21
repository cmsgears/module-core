<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\interfaces\resources;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IResourceService;
use cmsgears\core\common\services\interfaces\resources\IData;

/**
 * IFormFieldService provide service methods for form fields.
 *
 * @since 1.0.0
 */
interface IFormFieldService extends IResourceService, IData {

	// Data Provider ------

	public function getPageByFormId( $formId );

	// Read ---------------

	// Read - Models ---

	public function getByFormId( $formId );

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
