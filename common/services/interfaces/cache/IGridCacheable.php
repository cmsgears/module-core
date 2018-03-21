<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\interfaces\cache;

/**
 * IGridCacheable provide methods to cache models using GridCacheTrait.
 *
 * The grid can be cached to primary and secondary cache according to the cache configuration.
 *
 * @since 1.0.0
 */
interface IGridCacheable {

	public function cacheGrid( $model, $config = [] );
}
