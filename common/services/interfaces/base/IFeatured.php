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
 * IFeatured declare the methods provided by service trait - [[\cmsgears\core\common\services\traits\base\FeaturedTrait]].
 *
 * @since 1.0.0
 */
interface IFeatured {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	/**
	 * Return the pinned models based on multi-site configuration.
	 *
	 * @param array $config
	 * @return array
	 */
	public function getPinned( $config = [] );

	/**
	 * Return the pinned models based on multi-site configuration. The model must have type attribute.
	 *
	 * @param array $config
	 * @return array
	 */
	public function getPinnedByType( $type, $config = [] );

	/**
	 * Return the pinned models based on multi-site configuration.
	 *
	 * @param array $config
	 * @return array
	 */
	public function getFeatured( $config = [] );

	/**
	 * Return the pinned models based on multi-site configuration. The model must have type attribute.
	 *
	 * @param array $config
	 * @return array
	 */
	public function getFeaturedByType( $type, $config = [] );

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
