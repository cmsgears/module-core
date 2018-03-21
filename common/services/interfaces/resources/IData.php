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
 * IData declare the methods provided by service trait - [[\cmsgears\core\common\services\traits\resources\DataTrait]].
 *
 * @since 1.0.0
 */
interface IData {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getDataMeta( $model, $key, $assoc = false );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateDataMeta( $model, $key, $value );

	public function updateDataByParams( $model, $params = [], $config = [] );

	public function updateDataMetaObj( $model, $meta );

	// Delete -------------

	public function removeDataMeta( $model, $key );

	public function removeDataMetaObj( $model, $meta );

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
