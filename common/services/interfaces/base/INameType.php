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
 * INameType declare the methods provided by service trait - [[\cmsgears\core\common\services\traits\base\NameTypeTrait]].
 *
 * @since 1.0.0
 */
interface INameType {

	// Data Provider ------

	public function getPageByType( $type, $config = [] );

	// Read ---------------

	// Read - Models ---

	public function getByName( $name );

	public function getFirstByName( $name );

	public function getByType( $type );

	public function getFirstByType( $type );

	public function getByNameType( $name, $type );

	public function getFirstByNameType( $name, $type );

	public function searchByName( $name, $config = [] );

	public function searchByNameType( $name, $type, $config = [] );

	// Read - Lists ----

	public function getIdListByType( $type, $config = [] );

	public function getIdNameListByType( $type, $options = [] );

	// Read - Maps -----

	public function getIdNameMapByType( $type, $options = [] );

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
