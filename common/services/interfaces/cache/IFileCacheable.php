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
 * IFileCacheable provide methods to cache models using file having cache associated with
 * key and value.
 *
 * @since 1.0.0
 */
interface IFileCacheable {

	public function prepareFileCache( $model, $config = [] );

	public function cacheFile( $model, $config = [] );

}
