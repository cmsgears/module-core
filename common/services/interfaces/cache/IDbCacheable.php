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
 * IDbCacheable provide methods to cache models using database having cache associated with
 * key and value.
 *
 * @since 1.0.0
 */
interface IDbCacheable {

	public function cacheDb( $model, $config = [] );
}
