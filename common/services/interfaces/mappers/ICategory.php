<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\interfaces\mappers;

/**
 * ICategory declare the methods provided by service trait - [[\cmsgears\core\common\services\traits\mappers\CategoryTrait]].
 *
 * @since 1.0.0
 */
interface ICategory  {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByCategoryId( $categoryId, $config = [] );

	public function getByCategoryIds( $ids, $config = [] );

	public function getFeaturedByCategoryId( $categoryId, $config = [] );

	public function getByCategoryNodeId( $categoryId, $config = [] );

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
