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
 * IVisibility declare the methods provided by service trait - [[\cmsgears\core\common\services\traits\base\VisibilityTrait]].
 *
 * @since 1.0.0
 */
interface IVisibility {

	// Data Provider ------

	public function getPageByVisibility( $visibility, $config = [] );

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateVisibility( $model, $visibility );

	public function makePublic( $model );

	public function makeProtected( $model );

	public function makePrivate( $model );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
