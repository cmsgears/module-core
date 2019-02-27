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
 * IExcel declare the methods specific to Excel Spreadsheet based Import and Export.
 *
 * @since 1.0.0
 */
interface IExcel {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Import/Export ------

	public function importFromExcel( $filePath, $config = [] );

	public function exportToExcel( $filePath, $config = [] );

	public function filterToExcel( $filePath, $filters, $config = [] );

	// Additional ---------

}
