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
 * ISimilar declare the methods provided by service trait - [[\cmsgears\core\common\services\traits\base\SimilarTrait]].
 *
 * @since 1.0.0
 */
interface ISimilar {

	// Data Provider ------

	public function getPageForSimilar( $config = [] );

	// Read ---------------

	// Read - Models ---

	public function getSimilar( $config = [] );

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
