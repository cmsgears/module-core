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
 * IVisual declare the methods provided by service trait - [[\cmsgears\core\common\services\traits\resources\VisualTrait]].
 *
 * @since 1.0.0
 */
interface IVisual {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateAvatar( $model, $avatar );

	public function updateBanner( $model, $banner );

	public function updateVideo( $model, $video );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
