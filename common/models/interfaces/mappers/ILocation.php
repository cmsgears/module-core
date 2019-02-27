<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\interfaces\mappers;

/**
 * The ILocation declare the methods implemented by LocationTrait. It can be implemented
 * by entities, resources and models which need multiple locations.
 *
 * @since 1.0.0
 */
interface ILocation {

	/**
	 * Return all the location mappings associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\mappers\ModelLocation[]
	 */
	public function getModelLocations();

	/**
	 * Return all the active location mappings associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\mappers\ModelLocation[]
	 */
	public function getActiveModelLocations();

	/**
	 * Return the location mappings associated with the parent for given mapping type.
	 *
	 * @param string $type
	 * @param boolean $active
	 * @return \cmsgears\core\common\models\mappers\ModelLocation[]
	 */
	public function getModelLocationsByType( $type, $active = true );

	/**
	 * Return all the address associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\resources\Location[]
	 */
	public function getLocations();

	/**
	 * Return all the active address associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\resources\Location[]
	 */
	public function getActiveLocations();

	/**
	 * Return addresses associated with the parent for given mapping type.
	 *
	 * @param string $type
	 * @param boolean $active
	 * @return \cmsgears\core\common\models\resources\Location[]
	 */
	public function getLocationsByType( $type, $active = true );

}
