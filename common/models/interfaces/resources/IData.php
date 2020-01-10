<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\interfaces\resources;

/**
 * The IData declare the methods specific to data object.
 *
 * @since 1.0.0
 */
interface IData {

	/**
	 * Generate JSON and set the [[$data]] attribute.
	 *
	 * @param mixed $dataObject
	 * @return void
	 */
	public function generateJsonFromDataObject( $dataObject );

	/**
	 * Generate and return object using JSON data stored in [[$data]] attribute.
	 *
	 * @param boolean $assoc
	 * @return stdClass
	 */
	public function generateDataObjectFromJson( $assoc = false );

	/**
	 * Return data object property.
	 *
	 * @param string $name
	 * @param boolean $assoc
	 * @return mixed|null
	 */
	public function getDataMeta( $name, $assoc = false );

	/**
	 * Return configuration from data object property.
	 *
	 * @param string $name
	 * @param boolean $assoc
	 * @return mixed|null
	 */
	public function getDataConfigMeta( $name, $assoc = false );

	/**
	 * Return setting from data object property.
	 *
	 * @param string $name
	 * @param boolean $assoc
	 * @return mixed|null
	 */
	public function getDataSettingMeta( $name, $assoc = false );

	/**
	 * Set the property of data object.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @param boolean $assoc
	 * @return void
	 */
	public function setDataMeta( $name, $value, $assoc = false );

	/**
	 * Set the property of data object and also update the model to persist data object.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @param boolean $assoc
	 * @return void
	 */
	public function updateDataMeta( $name, $value, $assoc = false );

	/**
	 * Remove the property of data object without updating the model.
	 *
	 * @param string $name
	 * @param boolean $assoc
	 * @return void
	 */
	public function unsetDataMeta( $name, $assoc = false );

	/**
	 * Remove the property of data object and also update the model to persist data object.
	 *
	 * @param string $name
	 * @param boolean $assoc
	 * @return void
	 */
	public function removeDataMeta( $name, $assoc = false );
}
