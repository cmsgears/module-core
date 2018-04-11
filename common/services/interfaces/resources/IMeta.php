<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\interfaces\resources;

/**
 * IMeta declare the methods provided by service trait - [[\cmsgears\core\common\services\traits\resources\MetaTrait]].
 *
 * @since 1.0.0
 */
interface IMeta {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	public function getIdMetaMap( $model );

	public function getMetaMapByMetaType( $model, $type );

	public function getMetaNameValueMapByMetaType( $model, $type );

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
