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
 * The IGridCache declare the methods specific to grid based caching.
 *
 * @since 1.0.0
 */
interface IGridCache {

	/**
	 * Check whether the cache is valid.
	 *
	 * @return boolean
	 */
	public function isGridCacheValid();

	/**
	 * Generate JSON and set the [[$gridCache]] attribute.
	 *
	 * @param mixed $dataObject
	 * @return void
	 */
	public function generateJsonFromGridObject( $dataObject );

	/**
	 * Generate and return object using JSON data stored in [[$gridCache]] attribute.
	 *
	 * @param boolean $assoc
	 * @return stdClass
	 */
	public function generateGridObjectFromJson( $assoc = false );

	/**
	 * Find and return the property from grid cache object.
	 *
	 * @param string $name
	 * @param boolean $assoc
	 * @return mixed|null
	 */
	public function getGridCacheAttribute( $name, $assoc = false );

	/**
	 * Set the property of grid cache object.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @param boolean $assoc
	 * @return void
	 */
	public function setGridCacheAttribute( $name, $value, $assoc = false );

	/**
	 * Set the property of grid cache object and also update the model to persist grid cache.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @param boolean $assoc
	 * @return void
	 */
	public function updateGridCacheAttribute( $name, $value, $assoc = false );

	/**
	 * Remove the property of grid cache object and also update the model to persist grid cache.
	 *
	 * @param string $name
	 * @param boolean $assoc
	 * @return void
	 */
	public function removeGridCacheAttribute( $name, $assoc = false );
}
