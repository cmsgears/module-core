<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\traits\cache;

/**
 * GridCacheTrait provide methods to cache the grid data of a model for faster grid operations
 * including search, sort, filter and reporting.
 *
 * @since 1.0.0
 */
trait GridCacheTrait {

	public function prepareGridCache() {

		// Prepare the model for grid cache
	}

	public function cacheGrid( $model, $config = [] ) {

		// Cache the model for grid
	}

}
