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

	public function getDataKeyMeta( $model, $key, $assoc = false );

	public function getDataAttributeMeta( $model, $key, $assoc = false );

	public function getDataConfigMeta( $model, $key, $assoc = false );

	public function getDataSettingMeta( $model, $key, $assoc = false );

	public function getDataPluginMeta( $model, $key, $assoc = false );

	public function getDataCustomMeta( $model, $type, $key, $assoc = false );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateDataMeta( $model, $key, $value );

	public function updateDataByParams( $model, $params = [], $config = [] );

	public function updateDataMetaObj( $model, $meta );

	public function updateDataKeyObj( $model, $meta );

	public function updateDataAttributeObj( $model, $meta );

	public function updateDataConfigObj( $model, $meta );

	public function updateDataSettingObj( $model, $meta );

	public function updateDataPluginObj( $model, $meta );

	public function updateDataCustomObj( $model, $type, $meta );

	// Delete -------------

	public function removeDataMeta( $model, $key );

	public function removeDataMetaObj( $model, $meta );

	public function removeDataKeyObj( $model, $meta );

	public function removeDataAttributeObj( $model, $meta );

	public function removeDataConfigObj( $model, $meta );

	public function removeDataSettingObj( $model, $meta );

	public function removeDataPluginObj( $model, $meta );

	public function removeDataCustomObj( $model, $key, $meta );

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
