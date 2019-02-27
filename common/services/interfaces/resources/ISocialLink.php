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
 * ISocialLink declare the methods provided by service trait - [[\cmsgears\core\common\services\traits\resources\SocialLinkTrait]].
 *
 * @since 1.0.0
 */
interface ISocialLink {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getSocialLinks( $model );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateSocialLinks( $model, $links );

	public function updateSocialLink( $model, $link );

	// Delete -------------

	public function deleteSocialLink( $model, $link );

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
