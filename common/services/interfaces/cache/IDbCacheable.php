<?php
namespace cmsgears\core\common\services\interfaces\cache;

interface IDbCacheable extends ICacheable {

	public function cacheDb( $model, $config = [] );
}
